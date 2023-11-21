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

        if (Auth::guard('web')->attempt($credentials)) {
            $user = Auth::guard('web')->user();
        } elseif (Auth::guard('standar')->attempt($credentials)) {
            $user = Auth::guard('standar')->user();
        } elseif (Auth::guard('admin')->attempt($credentials)) {
            $user = Auth::guard('admin')->user();
        }

        else {
            return response()->json(['error' => 'Email ou senha inválidos!'], 401);
        }

        if ($user) {
            $customToken = Str::random(60);
            $cookieController = app(CookieController::class);
            return $cookieController->setAcessToken($customToken);
        } else {
            return response()->json(['error' => 'Erro ao obter informações do usuário.'], 500);
        }
    }

    protected function authenticated(Request $request, $user)
    {
        return redirect()->intended();
    }
}
