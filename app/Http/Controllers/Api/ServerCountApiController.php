<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Server;
use Illuminate\Http\Request;

class ServerCountApiController extends Controller
{

    public function count(Request $request)
    {
        $request->validate([
            'server_ip' => 'required',
            'active_users' => 'required|integer',
            'secret_key' => 'required'
        ]);

        if ($request->secret_key !== config('app.server_secret')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        Server::query()
            ->where('ip_address', $request->server_ip)
            ->update([
                'active_connection' => $request->active_users,
                'count_updated_at' => now(),
            ]);

        return response()->json(['status' => 'updatedd']);
    }

}
