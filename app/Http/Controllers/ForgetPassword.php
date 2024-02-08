<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\ResetPassword;

class ForgetPassword extends Controller
{
  public function getResetToken(Request $request)
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

    $token = Str::random(5);

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
        return response()->json(['success' => 'Token válido!'], 200);
      }
    } catch (\Exception $e) {
      return response()->json(['error' => 'Token inválido'], 401);
    }
  }

  public function resetPassword(Request $request)
  {
    $request->validate([
      'password' => "required"
    ]);

    $this->validateToken($request);
  }
}