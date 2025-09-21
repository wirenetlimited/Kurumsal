<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailService extends Model
{
    protected $fillable = [
        'service_id',
        'email_provider',
        'email_plan',
        'mailbox_count',
        'storage_limit',
        'smtp_server',
        'imap_server',
        'pop3_server',
        'webmail_url',
        'notes',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
