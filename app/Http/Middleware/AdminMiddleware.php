<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use App\Services\UserService;

class AdminMiddleware
{
  protected $userService;

  public function __construct(UserService $userService)
  {
    $this->userService = $userService;
  }

  public function handle(Request $request, Closure $next)
  {
    $bearer = $request->bearerToken();
    $user = $this->userService->findUserByToken($bearer);

    if (!$user->is_institution) {
      return response()->json(['error' => 'Unauthorized'], 403);
    }

    return $next($request);
  }
}