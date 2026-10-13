<?php

namespace App\Mail;

use App\Models\Pendaftaran;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * S2-07a — Email status penting pendaftaran (ID saja di subject).
 */
class StatusPendaftaranMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Pendaftaran $pendaftaran,
        public string $statusBaru,
        public ?string $catatan = null,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "[SKPI-{$this->pendaftaran->id}] Status pendaftaran: {$this->pendaftaran->labelStatus()}",
        );
    }

    public function content(): Content
    {
        return new Content(
            text: 'emails.status-pendaftaran',
            with: [
                'pendaftaran' => $this->pendaftaran,
                'statusBaru' => $this->statusBaru,
                'label' => $this->pendaftaran->labelStatus(),
                'catatan' => $this->catatan,
            ],
        );
    }
}
