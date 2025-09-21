<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SslCertificate extends Model
{
    protected $fillable = [
        'service_id',
        'certificate_type',
        'issuer',
        'serial_number',
        'issued_date',
        'expiry_date',
        'common_name',
        'subject_alternative_names',
        'key_size',
        'signature_algorithm',
        'notes',
    ];

    protected $casts = [
        'issued_date' => 'date',
        'expiry_date' => 'date',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
