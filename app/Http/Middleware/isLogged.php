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
        $user = $this->userService->findUserByToken($accessToken);

        $userToken = $user->custom_token;

        if (!$accessToken || $userToken !== $accessToken) {
            return response()->json(['error' => 'Usuário não authenticado!'], 401);
        }

        return $next($request);
    }
}
