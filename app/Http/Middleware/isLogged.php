<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class isLogged
{
    public function handle(Request $request, Closure $next)
    {

        if (auth()->guard(getActiveGuard())->check()) {            
            return $next($request);
        }

        return response()->json(['message' => 'Usuário não authenticado'], 401);
    }
}
