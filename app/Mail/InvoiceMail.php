<?php

namespace App\Mail;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvoiceMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = [60, 300, 900];

    public $invoice;
    public $customer;
    public $companyInfo;

    /**
     * Create a new message instance.
     */
    public function __construct(Invoice $invoice, array $companyInfo = [])
    {
        $this->invoice = $invoice;
        $this->customer = $invoice->customer;
        
        // Varsayılan şirket bilgileri
        if (empty($companyInfo)) {
            $this->companyInfo = [
                'name' => \App\Models\Setting::get('site_name', 'WH Kurumsal'),
                'email' => \App\Models\Setting::get('contact_email', 'info@whkurumsal.com'),
                'phone' => \App\Models\Setting::get('contact_phone', '+90 212 123 4567'),
                'address' => \App\Models\Setting::get('contact_address', 'İstanbul, Türkiye'),
                'website' => \App\Models\Setting::get('website', 'https://whkurumsal.com')
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
            subject: 'Fatura #' . $this->invoice->id . ' - ' . $this->companyInfo['name'],
            tags: ['invoice', 'billing'],
            metadata: [
                'invoice_id' => $this->invoice->id,
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
            markdown: 'emails.invoice',
            with: [
                'invoice' => $this->invoice,
                'customer' => $this->customer,
                'companyInfo' => $this->companyInfo,
                'dueDate' => $this->invoice->due_date ? \Carbon\Carbon::parse($this->invoice->due_date)->format('d.m.Y') : null,
                'daysRemaining' => $this->invoice->due_date ? (int)now()->diffInDays($this->invoice->due_date, false) : null,
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
