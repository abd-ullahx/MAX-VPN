<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Server;
use App\Services\TelegramNotificationService;
use Illuminate\Http\Request;

class ServerCountApiController extends Controller
{

    public function count(Request $request)
    {
        $request->validate([
            'server_ip' => 'required',
            'active_users' => 'required|integer',
            'secret_key' => 'required',
            'vpn_status' => 'nullable|string',
        ]);

        if ($request->secret_key !== config('app.server_secret')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Retrieve server first to trigger Eloquent model events
        $server = Server::where('ip_address', $request->server_ip)->first();

        if ($server) {
            $statusChanged = !$server->status;

            $server->update([
                'active_connection' => $request->active_users,
                'count_updated_at' => now(),
                'last_seen_at' => now(),
                'vpn_status' => $request->vpn_status ?? 'up',
                'status' => true, // Bring server back online if it was marked offline
            ]);

            if ($statusChanged) {
                // Send Telegram Restored Notification
                $countryName = $server->country->name ?? 'Unknown';
                $stateName = $server->state ?? 'N/A';
                $plan = $server->ispro ? 'Premium 💎' : 'Free 🆓';
                $activeConn = $server->active_connection ?? 0;
                $maxConn = $server->max_connection ?? 0;
                $vpnStatus = $server->vpn_status ?? 'up';

                $message = "🟢 <b>Server Restored: Server ID {$server->id} is ONLINE</b>\n\n"
                         . "🖥️ <b>IP Address:</b> <code>{$server->ip_address}</code>\n"
                         . "🌍 <b>Country:</b> {$countryName} ({$stateName})\n"
                         . "🏷️ <b>Plan:</b> {$plan}\n"
                         . "⚡ <b>Status:</b> ONLINE / ACTIVE\n"
                         . "🔌 <b>VPN Status:</b> {$vpnStatus}\n"
                         . "👥 <b>Active Connections:</b> {$activeConn} / {$maxConn}";

                TelegramNotificationService::send($message);

                \Illuminate\Support\Facades\Cache::forget('free_servers_list');
                \Illuminate\Support\Facades\Cache::forget('premium_servers_list');
                \Illuminate\Support\Facades\Cache::forget('recommended_servers_list');
            }
        }


        return response()->json(['status' => 'updatedd']);
    }

}
