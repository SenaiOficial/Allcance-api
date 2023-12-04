<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserIsLogged
{
    public function handle(Request $request, Closure $next)
    {
        $accessToken = $request->cookie('custom_token');

        if (!$accessToken) {
            return response()->json(['error' => 'Usuário não authenticado!'], 401);
        }

        return $next($request);
    }
}
