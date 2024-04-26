<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\UserService;
use Tymon\JWTAuth\Facades\JWTAuth;

class isLogged
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function handle(Request $request, Closure $next)
    {

        // $token = JWTAuth::getToken();
        // $token = JWTAuth::parseToken();

        dd(auth());

        // JWTAuth::checkOrFail($token);
        // $bearer = $request->bearerToken();
        // $user = $this->userService->findUserByToken($bearer);

        // if (!$bearer || !$user || !hash_equals($bearer, $user->custom_token)) {
        //     return response()->json(['error' => 'Usuário não autenticado!'], 401);
        // }

        return $next($request);
    }
}
