<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServerApiResource;
use App\Models\Server;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use MaxMind\Db\Reader;

class ApiServerController extends Controller
{

    public function servers(Request $request)
    {
        $ipAddress = $request->ip();
        $adType = $this->get_country_from_ip($ipAddress);

//        if($ipAddress == '203.99.179.180'){
//            $adType = 'yandex';  //yandex  or admob
//        }

        $freeServers = Cache::remember('free_servers_list', now()->addMinutes(10), function () {
            return Server::query()
                ->with('country')
                ->where('status', true)
                ->where('ispro', false)
                ->whereColumn('active_connection', '<=', 'max_connection')
                ->orderBy('active_connection')
                ->get();
        });

        $premiumServers = Cache::remember('premium_servers_list', now()->addMinutes(10), function () {
            return Server::query()
                ->with('country')
                ->where('status', true)
                ->where('ispro', true)
                ->whereColumn('active_connection', '<=', 'max_connection')
                ->orderBy('active_connection')
                ->get();
        });

        $recommendedServers = Cache::remember('recommended_servers_list', now()->addMinutes(10), function () {
            return Server::query()
                ->with('country')
                ->where('status', true)
                ->where('is_recommended', true)
                ->get();
        });

        return response()->json([
            'status' => true,
            'ad_type' => $adType,
            'free_servers' => ServerApiResource::collection($freeServers),
            'premium_servers' => ServerApiResource::collection($premiumServers),
            'recommended_servers' => ServerApiResource::collection($recommendedServers),
        ]);
    }

    private function get_country_from_ip($ip_address)
    {
        try {
            $iso = $ip_address
                ? (new Reader(public_path('uploads/GeoLite2-Country.mmdb')))->get($ip_address)['country']['iso_code'] ?? null
                : null;

            return $iso === 'RU' ? 'yandex' : 'admob';
        } catch (Exception $e) {
            return 'admob';
        }
    }

}
