<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Quote;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class QuoteSent extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = [60, 300, 900];

    /**
     * Create a new message instance.
     */
    public function __construct(public Quote $quote)
    {
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Teklif #'.$this->quote->number);
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(markdown: 'mail.quote_sent', with: [
            'quote' => $this->quote,
        ]);
    }

    public function attachments(): array
    {
        $pdf = PDF::loadView('quotes.pdf', ['quote' => $this->quote->load('items','customer')]);
        return [
            \Illuminate\Mail\Mailables\Attachment::fromData(fn() => $pdf->output(), 'teklif-'.$this->quote->number.'.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
