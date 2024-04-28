<?php

namespace App\Services;

class LoginService
{
  protected $guard;

  public function __construct()
  {
    $this->guard = getActiveGuard();
  }

  public function login($request)
  {
    $credentials = $request->getCredentials();

    foreach (guards() as $guard) {
      if ($token = auth($guard)->attempt($credentials)) {
        return response()->json([
          'success' => true,
          'message' => 'Sessão iniciada',
          'access_token' => $token,
          'type' => 'bearer'
        ]);
      }
    }

    return response()->json([
      'success' => false,
      'error' => 'Email ou senha inválidos!'
    ], 401);
  }

  public function logout()
  {
    if (auth($this->guard)->check()) auth($this->guard)->logout();

    return response()->json(['message' => 'Sessão finalizada'], 200);
  }
}
