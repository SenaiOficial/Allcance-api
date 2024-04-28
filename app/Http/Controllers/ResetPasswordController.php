<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
  protected $guard;

  public function __construct()
  {
    $this->guard = getActiveGuard();
  }

  public function resetPassword(Request $request)
  {
    try {
      $request->validate([
        'password' => 'required',
        'new_password' => 'required|string|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]+$/|different:password',
        'confirm_password' => 'required|same:new_password'
      ]);

      $user = auth($this->guard)->user();

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
}