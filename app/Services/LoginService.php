<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Services\UserService;

class LoginService
{
  protected $userService;

  public function __construct(UserService $userService)
  {
    $this->userService = $userService;
  }

  private function user(Request $request)
  {
    $bearer = $request->bearerToken();

    $user = $this->userService->findUserByToken($bearer);

    return $user;
  }

  public function login($request)
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

    return response()->json(['message' => 'Sessão iniciada', 'access_token' => $accessToken]);
  }

  public function logout($request)
  {
    $user = $this->user($request);
    $user->custom_token = null;
    $user->save();

    return response()->json(['message' => 'Sessão finalizada'], 200);
  }
}
