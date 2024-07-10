<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SuperAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $admin = auth()->guard('admin');

        if ($admin->check() && $admin->user()->is_admin) {
            return $next($request);
        }

        return response()->json(['error' => 'Unauthorized'], 403);
    }
}
