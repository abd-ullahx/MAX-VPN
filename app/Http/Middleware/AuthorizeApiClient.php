<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthorizeApiClient
{

    public function handle(Request $request, Closure $next)
    {
        if (!in_array($request->header('auth_token'), config('app.header_auth_token'))) {
            return response()->json(['status' => false, 'message' => 'Unauthorized Client'], 403);
        }

        return $next($request);
    }

}
