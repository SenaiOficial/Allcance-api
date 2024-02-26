<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\UserService;

class isLogged
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function handle(Request $request, Closure $next)
    {
        $accessToken = $request->cookie('custom_token');

        if (!$accessToken || $accessToken !== $this->userService->findUserByToken($accessToken)->custom_token) {
            return response()->json(['error' => 'Usuário não autenticado!'], 401);
        }

        return $next($request);
    }
}
