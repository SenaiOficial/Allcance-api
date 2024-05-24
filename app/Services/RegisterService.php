<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\UserAdmin;
use App\Models\UserPcd;
use App\Models\UserStandar;
use App\Models\UserDeficiency;
use App\Models\InstitutionalToken;

class RegisterService
{
  private function store($request, $model)
  {
    try {
      $validatedData = $request->validated();
      $validatedData['password'] = Hash::make($validatedData['password']);

      if ($request->has('pass_code')) {
        if ($this->validateAdminUser($validatedData)) return response()->json('Token Inválido', 401);

        if ($request->hasFile('profile_photo')) {
          $validatedData['profile_photo'] = Storage::disk('public')->put('images', $request->file('profile_photo'));
        }
      }

      $user = $model::create($validatedData);

      if ($request->has('pcd')) {
        $this->sendDeficiency($validatedData, $user);
      }

      if ($request->has('pass_code')) {
        $this->deleteAdminToken($validatedData['pass_code']);
      }

      $token = auth()->guard($user->getGuard())->login($user);

      return response()->json([
        'status' => 'success',
        'message' => 'Sessão iniciada',
        'access_token' => $token,
        'user' => getUserType($user)
      ]);
    } catch (\Exception $e) {
      return response()->json(['errors' => $e->getMessage()], 400);
    }
  }

  public function registerUser($request, $model)
  {
    return $this->store($request, $model);
  }

  private function getCamp($attribute, $param): array
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

  private function sendDeficiency($validatedData, $user): void
  {
    foreach ($validatedData['pcd'] as $deficiencyId) {
      UserDeficiency::create([
        'pcd_user_id' => $user->id,
        'deficiency_id' => $deficiencyId,
      ]);
    }
  }

  private function validateAdminUser($validatedData): bool
  {
    $providedToken = $validatedData['pass_code'];
    $storedToken = InstitutionalToken::query()
        ->where('token', $providedToken)
        ->where('expires_at', '>=', Carbon::now())
        ->first();

    if (!$storedToken || $providedToken !== $storedToken->token) return true;

    return false;
  }

  private function deleteAdminToken($providedToken): void
  {
    InstitutionalToken::where('token', '=', $providedToken)->delete();
  }
}
