<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Domain extends Model
{
    protected $fillable = [
        'service_id',
        'domain_name',
        'registrar_ref',
        'auth_code',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
