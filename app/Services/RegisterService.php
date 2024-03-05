<?php

namespace App\Services;

use App\Models\InstitutionalToken;
use App\Http\Controllers\CookieController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\UserAdmin;
use App\Models\UserPcd;
use App\Models\UserStandar;

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

  private function getCamp($attribute, $param)
  {
    $standarUserExists = UserStandar::where($attribute, $param)->exists();
    $pcdUserExists = UserPcd::where($attribute, $param)->exists();
    $adminExists = UserAdmin::where('email', $param)->exists();

    return [
      $standarUserExists,
      $pcdUserExists,
      $adminExists
    ];
  }

  private function checkExists(Request $request)
  {
    $emailResult = $this->getCamp('email', $request->email);
    $cpfResult = $this->getCamp('cpf', $request->cpf);

    if (in_array(true, $emailResult) || in_array(true, $cpfResult)) {
        return response()->json(['message' => 'Email ou CPF já cadastrado'], 404);
    }
  }

  public function checkEmailorCPF($request)
  {
    return $this->checkExists($request);
  }
}
