<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Provider extends Model
{
    protected $fillable = [
        'name',
        'type',
        'contact_info',
        'email',
        'phone',
        'website',
        'address',
        'notes',
    ];

    protected $casts = [
        'contact_info' => 'array',
        'type' => 'array',
    ];

    /**
     * Get the provider type as a string
     */
    public function getTypeStringAttribute(): string
    {
        if (is_array($this->type)) {
            return implode(', ', $this->type);
        }
        return (string) $this->type;
    }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }
}
