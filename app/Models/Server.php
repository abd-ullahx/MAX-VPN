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
        'ss_password',
        'ss_port',
        'ss_method',
        'last_seen_at',
        'vpn_status',
    ];

    protected $hidden = [
        'ss_password',
    ];

    protected $casts = [
        'last_seen_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::saved(function ($server) {
            \Illuminate\Support\Facades\Cache::forget('free_servers_list');
            \Illuminate\Support\Facades\Cache::forget('premium_servers_list');
            \Illuminate\Support\Facades\Cache::forget('recommended_servers_list');
        });
    }

    public function getCertificateAttribute()
    {
        return str_replace(["\r"], "", $this->ovpn);
    }

    public function country(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

}
