<?php

namespace App\Mail;

use App\Models\Quote;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class QuoteMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = [60, 300, 900];

    public $quote;
    public $customer;
    public $companyInfo;

    /**
     * Create a new message instance.
     */
    public function __construct(Quote $quote, array $companyInfo = [])
    {
        $this->quote = $quote;
        $this->customer = $quote->customer;
        
        // Varsayılan şirket bilgileri
        if (empty($companyInfo)) {
            $this->companyInfo = [
                'name' => \App\Models\Setting::get('site_name', 'WH Kurumsal'),
                'email' => \App\Models\Setting::get('contact_email', 'info@whkurumsal.com'),
                'phone' => \App\Models\Setting::get('contact_phone', '+90 212 123 4567'),
                'address' => \App\Models\Setting::get('contact_address', 'İstanbul, Türkiye'),
                'website' => \App\Models\Setting::get('website', 'https://whkurumsal.com'),
                'bank_name' => \App\Models\Setting::get('bank_name'),
                'bank_iban' => \App\Models\Setting::get('bank_iban'),
                'tax_number' => \App\Models\Setting::get('tax_number')
            ];
        } else {
            $this->companyInfo = $companyInfo;
        }
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Fiyat Teklifi #' . $this->quote->number . ' - ' . $this->companyInfo['name'],
            tags: ['quote', 'proposal'],
            metadata: [
                'quote_id' => $this->quote->id,
                'customer_id' => $this->customer->id,
            ],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.quote',
            with: [
                'quote' => $this->quote,
                'customer' => $this->customer,
                'companyInfo' => $this->companyInfo,
                'validUntil' => $this->quote->valid_until ? \Carbon\Carbon::parse($this->quote->valid_until)->format('d.m.Y') : null,
                'daysRemaining' => $this->quote->valid_until ? (int)now()->diffInDays($this->quote->valid_until, false) : null,
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
        return [];
    }
}
