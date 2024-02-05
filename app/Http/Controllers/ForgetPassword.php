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
      'email' => 'required|email|exists:standar_user,email|exists:pcd_users,email|exists:admin_user,email',
    ]);

    $token = Str::random(60);

    ResetPassword::create([
      'email' => $request->email,
      'token' => $token,
    ]);

    return response()->json(['token' => $token]);
  }

  public function validateToken($request)
  {
    $validateData = $request->validate();

    if ($request->has('token')) {
      $tokenReq = $validateData['token'];
      $userToken = ResetPassword::first()->reset_passwords;

      if ($tokenReq !== $userToken) {
        return response()->json(['error' => 'Token inválido'], 401);
      }
    }
  }

  public function resetPassword(Request $request)
  {
    $request->validate([
      'token' => "required",
      'password' => "required" 
    ]);

    $this->validateToken($request);
  }
}