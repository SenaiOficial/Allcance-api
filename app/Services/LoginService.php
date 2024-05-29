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
    foreach (guards() as $guard) {
      if ($token = auth($guard)->attempt($request->getCredentials())) {
        $user = auth($guard)->user();
        $config = $user->configs->first();
        $type = getUserType($user);

        if ($config) {
          $configs = [
            'text_size' => $config->text_size,
            'color_blindness' => $config->color_blindness
          ];
        }

        if (getUserType($user) !== 'default') {
          $info = [
            'name' => $user->institution_name,
            'cnpj' => $user->cnpj,
            'type' => $type
          ];
        } else {
          $info = [
            'cpf' => $user->cpf,
            'type' => $type
          ];
        }

        return response()->json([
          'success' => true,
          'message' => 'Sessão iniciada',
          'access_token' => $token,
          'user' => $info,
          'config' => $configs ?? null
        ]);
      }
    }

    return response()->json([
      'success' => false,
      'error' => 'Email ou senha incorretos!'
    ], 401);
  }

  public function logout()
  {
    if (auth($this->guard)->check()) auth($this->guard)->logout();

    return response()->json(['message' => 'Sessão finalizada'], 200);
  }
}
