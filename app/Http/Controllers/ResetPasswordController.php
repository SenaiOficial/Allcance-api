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
        'new_password' => [
          'required',
          'string',
          'min:8',
          'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]+$/',
          'different:password'
        ],
        'confirm_password' => 'required|same:new_password'
      ], [
        'password.required' => 'A senha atual é obrigatória.',
        'new_password.required' => 'A nova senha é obrigatória.',
        'new_password.string' => 'A nova senha deve ser um texto.',
        'new_password.min' => 'A nova senha deve ter no mínimo 8 caracteres.',
        'new_password.regex' => 'A nova senha deve conter pelo menos uma letra maiúscula, uma minúscula, um número e um caractere especial.',
        'new_password.different' => 'A nova senha deve ser diferente da senha atual.',
        'confirm_password.required' => 'A confirmação da nova senha é obrigatória.',
        'confirm_password.same' => 'A confirmação da senha deve corresponder à nova senha.'
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
