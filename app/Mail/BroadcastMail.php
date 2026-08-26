<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BroadcastMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $emailSubject;
    public string $emailBody;
    public ?string $attachmentPath;
    public ?string $attachmentName;

    /**
     * Create a new message instance.
     */
    public function __construct(string $subject, string $body, ?string $attachmentPath = null, ?string $attachmentName = null)
    {
        $this->emailSubject = $subject;
        $this->emailBody = $body;
        $this->attachmentPath = $attachmentPath;
        $this->attachmentName = $attachmentName;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->emailSubject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.broadcast',
            with: [
                'content' => $this->emailBody,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        if ($this->attachmentPath && file_exists(storage_path('app/' . $this->attachmentPath))) {
            return [
                \Illuminate\Mail\Mailables\Attachment::fromPath(storage_path('app/' . $this->attachmentPath))
                    ->as($this->attachmentName ?: basename($this->attachmentPath)),
            ];
        }

        return [];
    }
}
