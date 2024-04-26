<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class isLogged
{
    protected $guard;

    public function __construct()
    {
        $this->guard = getActiveGuard();
    }

    public function handle(Request $request, Closure $next)
    {
        if (auth($this->guard)->check()) {
            return $next($request);
        }

        return response()->json(['message' => 'Usuário não authenticado'], 401);
    }
}
