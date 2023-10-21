<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CustomTokenMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $providedToken = $request->header('Authorization'); 

            if ($providedToken === $user->custom_token) {
                return $next($request);
            }
        }

        return response()->json(['error' => 'Acesso não autorizado'], 401);
    }
}
