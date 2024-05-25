<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
  public function handle(Request $request, Closure $next)
  {
    $admin = auth()->guard('admin');

    if ($admin->check() && $admin->user()->is_institution && !$admin->user()->is_blocked) {
      return $next($request);
    }

    return response()->json(['error' => 'Unauthorized'], 403);
  }
}