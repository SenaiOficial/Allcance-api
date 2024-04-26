<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Services\UserService;
use Tymon\JWTAuth\Facades\JWTAuth;

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
    $guards = ['api', 'standar', 'admin'];

    // foreach ($guards as $guard) {
      if (Auth::guard('admin')->attempt($credentials)) {
        $user = Auth::guard('admin')->user();
      }
    // }

    if (!isset($user)) {
      return response()->json(['error' => 'Email ou senha inválidos!'], 401);
    }

    $token = auth('admin')->attempt($credentials);

    return response()->json([
      'status' => 'success',
      'message' => 'Sessão iniciada',
      'access_token' => $token,
      // 'refresh_token' => $token['refresh_token'],
      'type' => 'bearer'
    ]);
  }

  protected function getTokens($user)
  {
    $session_token = Str::random(60);
    $refresh_token = JWTAuth::fromUser($user);
    $user->update(['custom_token' => $session_token]);
    $user->update(['refresh_token' => $refresh_token]);

    return [
      'session_token' => $session_token,
      'refresh_token' => $refresh_token
    ];
  }

  public function logout($request)
  {
    $user = $this->user($request);
    $user->custom_token = null;
    $user->save();

    return response()->json(['message' => 'Sessão finalizada'], 200);
  }
}
