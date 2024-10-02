<?php

namespace App\Services;

use App\Services\User\BaseService;

class LoginService extends BaseService
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

        if ($config) {
          $configs = [
            'text_size' => $config->text_size,
            'color_blindness' => $config->color_blindness
          ];
        }
        
        return response()->json([
          'success' => true,
          'message' => 'Sessão iniciada',
          'access_token' => $token,
          'user' => $this->getUserInfos($user),
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
    $auth = auth($this->guard);

    if (!$auth) return response('Nenhum usuário logado', 404);

    if ($auth->check()) $auth->logout();

    return response()->json(['message' => 'Sessão finalizada'], 200);
  }
}
