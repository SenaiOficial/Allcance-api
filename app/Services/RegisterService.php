<?php

namespace App\Services;

use App\Models\InstitutionalToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\UserAdmin;
use App\Models\UserPcd;
use App\Models\UserStandar;
use App\Models\UserDeficiency;

class RegisterService
{
  private function store($request, $model)
  {
    try {
      $validatedData = $request->validated();
      $validatedData['password'] = Hash::make($validatedData['password']);

      
      if ($request->has('pass_code')) {
        $this->validateAdminUser($validatedData);

        if ($request->hasFile('profile_photo')) {
          $validatedData['profile_photo'] = Storage::disk('public')->put('images', $request->file('profile_photo'));
        }
      }

      $user = $model::create($validatedData);

      if ($request->has('pcd')) {
        $this->sendDeficiency($validatedData, $user);
      }

      $token = auth()->guard($user->getGuard())->login($user);

      return response()->json([
        'status' => 'success',
        'message' => 'Sessão iniciada',
        'refresh_token' => $token,
        'type' => 'bearer'
    ]);
    } catch (\Exception $e) {
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

  private function sendDeficiency($validatedData, $user)
  {
    foreach ($validatedData['pcd'] as $deficiencyId) {
      UserDeficiency::create([
        'pcd_user_id' => $user->id,
        'deficiency_id' => $deficiencyId,
      ]);
    }
  }

  private function validateAdminUser($validatedData)
  {
    $providedToken = $validatedData['pass_code'];
    $storedToken = InstitutionalToken::where('institutional_token', $providedToken);

    if ($providedToken !== $storedToken) return response()->json(['error' => 'Token inválido'], 400);
  }
}
