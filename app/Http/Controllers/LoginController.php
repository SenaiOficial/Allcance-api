<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

        $acessToken = Str::random(60);
        $userId = $user->id;
        $userType = $user->getTable();

        $user->update(['custom_token' => $acessToken]);

        $cookieController = app(CookieController::class);
        return $cookieController->setAcessToken($acessToken, $userId, $userType);
    }
}
