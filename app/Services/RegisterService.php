<?php

namespace App\Services;

use App\Models\InstitutionalToken;
use App\Http\Controllers\CookieController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RegisterService
{

  private function store($request, $model)
  {
    try {
      $validatedData = $request->validated();
      $validatedData['password'] = Hash::make($validatedData['password']);

      if ($request->has('pass_code')) {
        $providedToken = $validatedData['pass_code'];
        $storedToken = InstitutionalToken::first()->institutional_token;

        if ($providedToken !== $storedToken) {
          return response()->json(['error' => 'Token inválido'], 400);
        }
      }

      $user = $model::create($validatedData);

      if (!$user) {
        Log::error('Erro ao criar usuário');
        return response()->json(['error' => 'Erro ao criar usuário']);
      }

      $accessToken = Str::random(60);
      $user->update(['custom_token' => $accessToken]);

      $cookieController = app(CookieController::class);
      return $cookieController->setAccessToken($accessToken);
    } catch (\Exception $e) {
      if ($e->getCode() == '23000') {
        return response()->json(['error' => 'Email ou CPF já cadastrado'], 400);
      }
      return response()->json(['errors' => $e->getMessage()], 400);
    }
  }

  public function registerUser($request, $model)
  {
    return $this->store($request, $model);
  }
}
