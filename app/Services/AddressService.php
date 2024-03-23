<?php

namespace app\Services;

use App\Services\UserService;
use App\Models\UserPcd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AddressService
{
  protected $userService;

  public function __construct(UserService $userService)
  {
      $this->userService = $userService;
  }

  private function getUser(Request $request)
  {
      $bearer = $request->bearerToken();

      $user = $this->userService->findUserByToken($bearer);

      return $user;
  }

  public function update(Request $request)
  {
      $user = $this->getUser($request);

      $this->validateUser($user);

      try {
          $requestData = $request->only(['cep', 'neighborhood', 'street', 'street_number', 'street_complement']);

          $this->rules($requestData);

          $userPcd = UserPcd::find($user->id);

          $userPcd->update($requestData);

          return response()->json(['message' => 'Campos atualizados com sucesso'], 200);
      } catch (ValidationException $e) {
        return response()->json(['error' => $e->errors()], 422);
       } catch (\Exception $e) {
          return response()->json(['error' => $e->getMessage()], 500);
      }
  }

  private function rules($data)
  {
    $rules = [
      'cep' => ['required', 'string', 'size:8'],
      'neighborhood' => ['required', 'string', 'max:100'],
      'street' => ['required', 'string', 'max:255'],
      'street_number' => ['required', 'numeric', 'max:4'],
      'street_complement' => ['nullable', 'string', 'max:255'],
    ];

    $validator = Validator::make($data, $rules);

    if ($validator->fails()) {
      throw ValidationException::withMessages($validator->errors()->toArray());
    }
  }

  private function validateUser($user)
  {
      if ($user->getTable() !== 'pcd_users') abort(401, 'Unauthorized');
  }
}