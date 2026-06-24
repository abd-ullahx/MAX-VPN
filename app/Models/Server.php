<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Server extends Model
{

    public const STATUS_CONNECTED = "connected";
    public const STATUS_DISCONNECTED = "disconnected";

    protected $fillable = [
        'country_id',
        'state',
        'ovpn',
        'ispro',
        'is_recommended',
        'ip_address',
        'priority',
        'active_connection',
        'max_connection',
        'source',
        'status',
        'switch',
        'username',
        'password',
        'count_updated_at',
    ];

    public function getCertificateAttribute()
    {
        return str_replace(["\r"], "", $this->ovpn);
    }

    public function country(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

}
