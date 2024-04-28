<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
  public function handle(Request $request, Closure $next)
  {
    if (auth()->guard('admin')->check()) {
      return $next($request);
    }

    return response()->json(['error' => 'Unauthorized'], 403);
  }
}