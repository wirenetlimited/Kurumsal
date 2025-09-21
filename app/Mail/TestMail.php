<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TestMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = [60, 300, 900];

    public $subject;
    public $message;
    public $companyInfo;

    /**
     * Create a new message instance.
     */
    public function __construct($subject, $message)
    {
        $this->subject = $subject;
        $this->message = $message;
        $this->companyInfo = [
            'name' => 'WH Kurumsal',
            'email' => 'info@whkurumsal.com',
            'phone' => '+90 212 123 4567',
            'address' => 'İstanbul, Türkiye',
            'website' => 'https://whkurumsal.com'
        ];
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'TEST: ' . $this->subject,
            tags: ['test', 'smtp'],
            metadata: [
                'test_type' => 'smtp_configuration',
                'timestamp' => now()->toISOString(),
            ],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.test',
            with: [
                'subject' => $this->subject,
                'message' => $this->message,
                'companyInfo' => $this->companyInfo,
                'timestamp' => now()->format('d.m.Y H:i:s'),
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
