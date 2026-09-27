<?php

namespace App\Mail;

use App\Models\AbuseReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class AbuseReportReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public AbuseReport $report)
    {
        //
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nieuwe abuse-melding: '.$this->report->category_label,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.abuse-report-received',
            with: [
                'report' => $this->report,
            ],
        );
    }

    public function attachments(): array
    {
        if (! $this->report->attachment_path) {
            return [];
        }

        return [
            Attachment::fromStorageDisk('private', $this->report->attachment_path)
                ->as(basename($this->report->attachment_path)),
        ];
    }
}
