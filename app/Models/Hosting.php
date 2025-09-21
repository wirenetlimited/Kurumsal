<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Hosting extends Model
{
    protected $table = 'hostings';
    
    protected $fillable = [
        'service_id',
        'hosting_provider',
        'hosting_plan',
        'plan_name',
        'server_name',
        'ip_address',
        'cpanel_username',
        'panel_ref',
        'disk_space',
        'bandwidth',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
