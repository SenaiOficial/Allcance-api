<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
  protected $userService;

  public function __construct(UserService $userService)
  {
    $this->userService = $userService;
  }

  public function resetPassword(Request $request)
  {
    try {
      $request->validate([
        'password' => 'required',
        'new_password' => 'required|string|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]+$/',
      ]);

      $user = $this->getUser($request);

      if (!Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Senha atual incorreta'], 400);
      }

      $user->password = Hash::make($request->new_password);
      $user->save();

      return response()->json(['message' => 'Senha alterada com sucesso'], 200);

    } catch (\Exception $e) {
      return response()->json(['message' => 'Erro ao redefinir a senha: ' . $e->getMessage()], 500);
    }
  }

  private function getUser(Request $request)
  {
    $bearer = $request->bearerToken();

    $user = $this->userService->findUserByToken($bearer);

    return $user;
  }
}