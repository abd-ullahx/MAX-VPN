<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Server;
use App\Services\TelegramNotificationService;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    $staleServers = Server::where('status', true)
        ->where(function ($query) {
            $query->where('last_seen_at', '<', now()->subMinutes(2))
                  ->orWhere(function ($q) {
                      $q->whereNull('last_seen_at')
                        ->where('created_at', '<', now()->subMinutes(2));
                  })
                  ->orWhere('vpn_status', 'down');
        })
        ->get();

    if ($staleServers->isNotEmpty()) {
        foreach ($staleServers as $server) {
            $server->update([
                'status' => false,
            ]);

            // Send Telegram Alert
            $countryName = $server->country->name ?? 'Unknown';
            $stateName = $server->state ?? 'N/A';
            $plan = $server->ispro ? 'Premium 💎' : 'Free 🆓';
            $activeConn = $server->active_connection ?? 0;
            $maxConn = $server->max_connection ?? 0;
            $vpnStatus = $server->vpn_status ?? 'down';

            $message = "🔴 <b>Server Alert: Server ID {$server->id} is DOWN</b>\n\n"
                     . "🖥️ <b>IP Address:</b> <code>{$server->ip_address}</code>\n"
                     . "🌍 <b>Country:</b> {$countryName} ({$stateName})\n"
                     . "🏷️ <b>Plan:</b> {$plan}\n"
                     . "⚡ <b>Status:</b> DOWN / INACTIVE (No response for 2 minutes)\n"
                     . "🔌 <b>VPN Status:</b> {$vpnStatus}\n"
                     . "👥 <b>Active Connections:</b> {$activeConn} / {$maxConn}";

            TelegramNotificationService::send($message);
        }
        \Illuminate\Support\Facades\Cache::forget('free_servers_list');
        \Illuminate\Support\Facades\Cache::forget('premium_servers_list');
        \Illuminate\Support\Facades\Cache::forget('recommended_servers_list');
    }
})->everyMinute();

