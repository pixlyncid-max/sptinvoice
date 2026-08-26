<?php

namespace App\Http\Controllers\EmailMarketing;

use App\Http\Controllers\Controller;
use App\Jobs\SendCampaignEmail;
use App\Models\EmailCampaign;
use App\Models\EmailCampaignRecipient;
use App\Models\EmailContact;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EmailCampaignController extends Controller
{
    public function index(Request $request)
    {
        $query = EmailCampaign::with(['template', 'creator'])->withCount('recipients');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        $campaigns = $query->latest()->paginate(10)->withQueryString();

        // Overall stats
        $totalCampaigns = EmailCampaign::count();
        $totalSent = EmailCampaign::sum('sent_count');
        $totalFailed = EmailCampaign::sum('failed_count');

        return view('email-marketing.campaigns.index', compact(
            'campaigns',
            'totalCampaigns',
            'totalSent',
            'totalFailed'
        ));
    }

    public function create()
    {
        $templates = EmailTemplate::latest()->get();
        $subscribedContacts = EmailContact::where('is_subscribed', true)->get();
        $totalSubscribed = $subscribedContacts->count();

        return view('email-marketing.campaigns.create', compact('templates', 'subscribedContacts', 'totalSubscribed'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'template_id' => 'required|exists:email_templates,id',
            'recipient_type' => 'required|in:all_subscribed,custom',
            'selected_contacts' => 'nullable|array',
            'selected_contacts.*' => 'exists:email_contacts,id',
        ]);

        if ($request->recipient_type === 'custom' && empty($request->selected_contacts)) {
            return back()->withErrors(['selected_contacts' => 'Pilih minimal 1 kontak penerima.'])->withInput();
        }

        // Get recipients (ONLY subscribed contacts)
        if ($request->recipient_type === 'all_subscribed') {
            $contacts = EmailContact::where('is_subscribed', true)->get();
        } else {
            $contacts = EmailContact::where('is_subscribed', true)
                ->whereIn('id', $request->selected_contacts)
                ->get();
        }

        if ($contacts->isEmpty()) {
            return back()->withErrors(['selected_contacts' => 'Tidak ada kontak aktif (subscribed) yang dipilih.'])->withInput();
        }

        DB::beginTransaction();
        try {
            $campaign = EmailCampaign::create([
                'name' => $request->name,
                'subject' => $request->subject,
                'template_id' => $request->template_id,
                'status' => 'queued',
                'total_recipients' => $contacts->count(),
                'sent_count' => 0,
                'failed_count' => 0,
                'created_by' => Auth::id(),
            ]);

            $rateLimit = (int) env('EMAIL_RATE_LIMIT', 5);
            $rateInterval = (int) env('EMAIL_RATE_INTERVAL', 60);
            $stepSeconds = max(1, (int) floor($rateInterval / max(1, $rateLimit)));

            $index = 0;
            foreach ($contacts as $contact) {
                $recipient = EmailCampaignRecipient::create([
                    'campaign_id' => $campaign->id,
                    'contact_id' => $contact->id,
                    'email' => $contact->email,
                    'status' => 'pending',
                    'attempts' => 0,
                ]);

                // Dispatch individual queue job with staggered delay
                $delaySeconds = $index * $stepSeconds;
                SendCampaignEmail::dispatch($recipient->id)->delay(now()->addSeconds($delaySeconds));

                $index++;
            }

            DB::commit();

            return redirect()->route('email-marketing.campaigns.show', $campaign)
                ->with('success', "Campaign berhasil dibuat dan {$campaign->total_recipients} email telah dimasukkan ke dalam antrean (Queue).");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat campaign: ' . $e->getMessage())->withInput();
        }
    }

    public function show(EmailCampaign $campaign, Request $request)
    {
        $campaign->load(['template', 'creator']);

        $recipientsQuery = $campaign->recipients()->with('contact');

        if ($request->filled('status')) {
            $recipientsQuery->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $recipientsQuery->where('email', 'like', "%{$search}%");
        }

        $recipients = $recipientsQuery->latest()->paginate(20)->withQueryString();

        // Calculate current statistics
        $stats = [
            'total' => $campaign->total_recipients,
            'queued' => $campaign->recipients()->where('status', 'pending')->count(),
            'sent' => $campaign->recipients()->where('status', 'sent')->count(),
            'failed' => $campaign->recipients()->where('status', 'failed')->count(),
            'skipped' => $campaign->recipients()->where('status', 'skipped')->count(),
        ];

        return view('email-marketing.campaigns.show', compact('campaign', 'recipients', 'stats'));
    }

    public function retryFailed(EmailCampaign $campaign)
    {
        $failedRecipients = $campaign->recipients()->where('status', 'failed')->get();

        if ($failedRecipients->isEmpty()) {
            return back()->with('info', 'Tidak ada email gagal yang dapat di-retry.');
        }

        $rateLimit = (int) env('EMAIL_RATE_LIMIT', 5);
        $rateInterval = (int) env('EMAIL_RATE_INTERVAL', 60);
        $stepSeconds = max(1, (int) floor($rateInterval / max(1, $rateLimit)));

        $campaign->update(['status' => 'sending']);

        $index = 0;
        foreach ($failedRecipients as $recipient) {
            $recipient->update([
                'status' => 'pending',
                'error_message' => null,
            ]);

            $delaySeconds = $index * $stepSeconds;
            SendCampaignEmail::dispatch($recipient->id)->delay(now()->addSeconds($delaySeconds));
            $index++;
        }

        return back()->with('success', "{$failedRecipients->count()} email gagal berhasil dimasukkan kembali ke antrean.");
    }

    public function destroy(EmailCampaign $campaign)
    {
        $campaign->delete();
        return redirect()->route('email-marketing.campaigns.index')->with('success', 'Campaign berhasil dihapus.');
    }
}
