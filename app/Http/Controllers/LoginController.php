<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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
            $user->update(['custom_token' => $customToken]);
            return response()->json(['message' => 'Você foi logado com sucesso!', 'custom_token' => $customToken], 200);
        } else {
            return response()->json(['error' => 'Erro ao obter informações do usuário.'], 500);
        }
    }

    protected function authenticated(Request $request, $user)
    {
        return redirect()->intended();
    }
}
