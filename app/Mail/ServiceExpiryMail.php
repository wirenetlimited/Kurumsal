<?php

namespace App\Mail;

use App\Models\Service;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ServiceExpiryMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = [60, 300, 900];

    public $service;
    public $customer;
    public $companyInfo;
    public $daysRemaining;

    /**
     * Create a new message instance.
     */
    public function __construct(Service $service, int $daysRemaining)
    {
        $this->service = $service;
        $this->customer = $service->customer;
        $this->daysRemaining = $daysRemaining;
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
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $urgency = $this->daysRemaining <= 7 ? 'ACİL' : 'BİLGİLENDİRME';
        
        return new Envelope(
            subject: $urgency . ' - Hizmet Süresi Dolma Uyarısı - ' . $this->companyInfo['name'],
            tags: ['service', 'expiry', 'warning'],
            metadata: [
                'service_id' => $this->service->id,
                'customer_id' => $this->customer->id,
                'days_remaining' => $this->daysRemaining,
            ],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.service-expiry',
            with: [
                'service' => $this->service,
                'customer' => $this->customer,
                'companyInfo' => $this->companyInfo,
                'daysRemaining' => $this->daysRemaining,
                'expiryDate' => $this->service->end_date ? \Carbon\Carbon::parse($this->service->end_date)->format('d.m.Y') : null,
                'serviceType' => $this->getServiceTypeName(),
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

    /**
     * Get service type name in Turkish
     */
    private function getServiceTypeName(): string
    {
        $types = [
            'domain' => 'Domain',
            'hosting' => 'Hosting',
            'ssl' => 'SSL Sertifikası',
            'email' => 'E-posta Hizmeti',
        ];

        return $types[$this->service->service_type] ?? 'Hizmet';
    }
}
