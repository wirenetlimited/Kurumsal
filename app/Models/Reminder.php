<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Reminder extends Model
{
    protected $fillable = [
        'remindable_type',
        'remindable_id',
        'reminder_type',
        'sent_at',
        'channel',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function remindable(): MorphTo
    {
        return $this->morphTo();
    }
}
