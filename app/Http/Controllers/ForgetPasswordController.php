<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use App\Models\ResetPassword;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Services\UserService;

class ForgetPasswordController extends Controller
{
  protected $userService;

  public function __construct(UserService $userService)
  {
    $this->userService = $userService;
  }

  public function getResetToken(Request $request)
  {
    $this->validateEmail($request);

    $token = Str::random(10);

    ResetPassword::create([
      'email' => $request->email,
      'token' => $token,
    ]);

    return response()->json(['token' => $token]);
  }

  public function validateToken(Request $request)
  {
    try {
      $validateData = $request->validate([
        'token' => 'required|exists:reset_passwords'
      ]);

      $userToken = ResetPassword::where('token', $validateData['token'])->first();

      if ($userToken) {
        $resetUrl = URL::to('/reset-password?token=' . $validateData['token']);

        return response()->json(['success' => 'Token válido!', 'reset_url' => $resetUrl], 200);
      }
    } catch (\Exception $e) {
      return response()->json(['error' => 'Token inválido'], 401);
    }
  }

  public function resetForgotenPassword(Request $request)
  {
    $request->validate([
      'token' => 'required|exists:reset_passwords',
      'password' => 'required|string|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]+$/'
    ]);

    $resetData = $request->only('token', 'password');
    $resetToken = ResetPassword::where('token', $resetData['token'])->first();

    if ($resetData) {
      $table = $this->getTable($request);
      $success = $this->updatePasswordInTable($table, $resetToken->email, $resetData['password']);

      if ($success) {
        $resetToken->delete();
        return response()->json(['success' => 'Senha redefinida com sucesso!'], 200);
      }
    }

    return response()->json(['error' => 'Erro ao redefinir senha. Token inválido ou usuário não encontrado.'], 401);
  }

  private function getUser(Request $request)
  {
    $bearer = $request->bearerToken();

    $user = $this->userService->findUserByToken($bearer);

    return $user;
  }

  private function getTable(Request $request)
  {
    $user = $this->getUser($request);

    return $user->getTable();
  }

  private function updatePasswordInTable($table, $email, $password)
  {
    $affectedRows = DB::table($table)
      ->where('email', $email)
      ->update(['password' => Hash::make($password)]);

    return $affectedRows > 0;
  }

  private function validateEmail(Request $request)
  {
    $request->validate([
      'email' => [
        'required',
        'email',
        function ($attribute, $value, $fail) {
          $existsInAnyTable = collect(['standar_user', 'admin_user', 'pcd_users'])
            ->filter(function ($table) use ($value) {
              return \DB::table($table)->where('email', $value)->exists();
            })
            ->isNotEmpty();

          if (!$existsInAnyTable) {
            $fail('Email não encontrado.');
          }
        },
      ],
    ]);
  }
}