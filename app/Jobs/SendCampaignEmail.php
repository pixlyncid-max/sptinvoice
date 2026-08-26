<?php

namespace App\Jobs;

use App\Mail\BroadcastMail;
use App\Models\EmailCampaign;
use App\Models\EmailCampaignRecipient;
use App\Models\EmailContact;
use App\Models\EmailLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendCampaignEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $recipientId;
    public int $tries;
    public int $maxExceptions = 3;
    public int $backoff = 30; // seconds before retry

    /**
     * Create a new job instance.
     */
    public function __construct(int $recipientId)
    {
        $this->recipientId = $recipientId;
        $this->tries = (int) env('EMAIL_MAX_ATTEMPTS', 3);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $recipient = EmailCampaignRecipient::with(['campaign.template', 'contact'])->find($this->recipientId);

        if (!$recipient) {
            return;
        }

        // Idempotency: Don't resend if already sent
        if ($recipient->status === 'sent') {
            return;
        }

        $campaign = $recipient->campaign;
        if (!$campaign || in_array($campaign->status, ['paused', 'failed'])) {
            return;
        }

        // Check if contact has unsubscribed
        if ($recipient->contact && !$recipient->contact->is_subscribed) {
            $recipient->update([
                'status' => 'skipped',
                'error_message' => 'Kontak telah unsubscribe.',
            ]);
            $this->updateCampaignStats($campaign);
            return;
        }

        $recipient->increment('attempts');

        try {
            // Prepare template & variables
            $template = $campaign->template;
            $contact = $recipient->contact;

            $name = $contact ? $contact->name : explode('@', $recipient->email)[0];
            $company = $contact ? ($contact->company ?? '') : '';
            $email = $recipient->email;
            $unsubscribeUrl = $contact
                ? route('email-marketing.unsubscribe', ['token' => $contact->unsubscribe_token])
                : route('email-marketing.unsubscribe', ['token' => 'general']);

            $subject = $campaign->subject;
            $body = $template ? $template->body : '';

            $variables = [
                '{{name}}' => htmlspecialchars($name, ENT_QUOTES, 'UTF-8'),
                '{{email}}' => htmlspecialchars($email, ENT_QUOTES, 'UTF-8'),
                '{{company}}' => htmlspecialchars($company, ENT_QUOTES, 'UTF-8'),
                '{{unsubscribe_url}}' => $unsubscribeUrl,
            ];

            $renderedSubject = str_replace(array_keys($variables), array_values($variables), $subject);
            $renderedBody = str_replace(array_keys($variables), array_values($variables), $body);

            // Send individual email
            Mail::to($recipient->email)->send(new BroadcastMail($renderedSubject, $renderedBody));

            // Mark as sent
            $recipient->update([
                'status' => 'sent',
                'sent_at' => now(),
                'error_message' => null,
            ]);

            // Create log
            EmailLog::create([
                'campaign_id' => $campaign->id,
                'recipient_email' => $recipient->email,
                'subject' => $renderedSubject,
                'status' => 'sent',
                'sent_at' => now(),
            ]);

            $this->updateCampaignStats($campaign);

        } catch (Throwable $e) {
            $isLastAttempt = $this->attempts() >= $this->tries;

            $recipient->update([
                'status' => $isLastAttempt ? 'failed' : 'pending',
                'error_message' => $e->getMessage(),
            ]);

            if ($isLastAttempt) {
                EmailLog::create([
                    'campaign_id' => $campaign->id,
                    'recipient_email' => $recipient->email,
                    'subject' => $campaign->subject,
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                    'sent_at' => now(),
                ]);

                $this->updateCampaignStats($campaign);
            }

            throw $e;
        }
    }

    /**
     * Update campaign status and counter totals
     */
    private function updateCampaignStats(EmailCampaign $campaign): void
    {
        $sentCount = $campaign->recipients()->where('status', 'sent')->count();
        $failedCount = $campaign->recipients()->whereIn('status', ['failed', 'skipped'])->count();
        $totalRecipients = $campaign->total_recipients;

        $newStatus = $campaign->status;
        if (($sentCount + $failedCount) >= $totalRecipients) {
            $newStatus = 'completed';
        } elseif ($campaign->status === 'queued') {
            $newStatus = 'sending';
        }

        $campaign->update([
            'sent_count' => $sentCount,
            'failed_count' => $failedCount,
            'status' => $newStatus,
        ]);
    }

    /**
     * Handle job failure after max attempts
     */
    public function failed(Throwable $exception): void
    {
        $recipient = EmailCampaignRecipient::with('campaign')->find($this->recipientId);
        if ($recipient) {
            $recipient->update([
                'status' => 'failed',
                'error_message' => $exception->getMessage(),
            ]);

            if ($recipient->campaign) {
                $this->updateCampaignStats($recipient->campaign);
            }
        }
    }
}
