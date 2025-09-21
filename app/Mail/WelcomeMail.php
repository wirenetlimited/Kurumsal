<?php

namespace App\Mail;

use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = [60, 300, 900];

    public $customer;
    public $companyInfo;

    /**
     * Create a new message instance.
     */
    public function __construct(Customer $customer, array $companyInfo = [])
    {
        $this->customer = $customer;
        
        // Varsayılan şirket bilgileri
        if (empty($companyInfo)) {
            $this->companyInfo = [
                'name' => \App\Models\Setting::get('site_name', 'WH Kurumsal'),
                'email' => \App\Models\Setting::get('contact_email', 'info@whkurumsal.com'),
                'phone' => \App\Models\Setting::get('contact_phone', '+90 212 123 4567'),
                'address' => \App\Models\Setting::get('contact_address', 'İstanbul, Türkiye'),
                'website' => \App\Models\Setting::get('website', 'https://whkurumsal.com'),
                'support_email' => \App\Models\Setting::get('support_email', 'destek@whkurumsal.com')
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
            subject: 'Hoş Geldiniz! - ' . $this->companyInfo['name'] . ' Ailesine Katıldınız',
            tags: ['welcome', 'onboarding'],
            metadata: [
                'customer_id' => $this->customer->id,
                'customer_type' => $this->customer->customer_type,
            ],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.welcome',
            with: [
                'customer' => $this->customer,
                'companyInfo' => $this->companyInfo,
                'customerType' => $this->getCustomerTypeName(),
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
     * Get customer type name in Turkish
     */
    private function getCustomerTypeName(): string
    {
        $types = [
            'individual' => 'Bireysel',
            'corporate' => 'Kurumsal',
            'bireysel' => 'Bireysel',
            'kurumsal' => 'Kurumsal',
        ];

        return $types[$this->customer->customer_type] ?? 'Müşteri';
    }
}
