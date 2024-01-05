<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Http\Controllers\CookieController;

class LoginController extends Controller
{
    public function show()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->getCredentials();
        $guards = ['web', 'standar', 'admin'];

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->attempt($credentials)) {
                $user = Auth::guard($guard)->user();
                break;
            }
        }

        if (!isset($user)) {
            return response()->json(['error' => 'Email ou senha inválidos!'], 401);
        }

        $accessToken = Str::random(60);
        $user->update(['custom_token' => $accessToken]);

        $cookieController = app(CookieController::class);
        return $cookieController->setAccessToken($accessToken);
    }
}
