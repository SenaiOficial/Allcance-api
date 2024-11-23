<?php

namespace App\Services\User;

use App\Models\UserAdmin;

class SuperAdminService
{
  protected $user;

  public function __construct()
  {
    $this->user = auth('admin')->user();
  }

  public function block($request)
  {
    $user = $this->user;

    try {
      $request->validate([
        'cnpj' => 'required',
        'password' => 'required'
      ]);

      if (checkUserPassword($request->password, $user->password)) {
        return response()->json([
          'success' => false,
          'message' => 'Senha inválida'
        ], 401);
      }

      $institution = UserAdmin::where('cnpj', $request->cnpj)->first();

      if (!$institution) {
        return response()->json([
          'success' => false,
          'message' => 'Instituição não encontrada'
        ], 404);
      }

      $institution->blocked = !$institution->blocked;
      $institution->save();

      return response()->json([
        'success' => true,
        'message' => 'Instituição bloqueada/desbloqueada com sucesso'
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => $e->getMessage()
      ], 400);
    }
  }
}
