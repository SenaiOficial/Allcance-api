<?php

namespace App\Services;

use App\Models\Deficiency;
use App\Models\DeficiencyTypes;
use App\Services\UserService;
use Illuminate\Http\Request;

class DeficiencyService
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

  public function sendDeficiencyTypes()
  {
    $deficiencyTypes = DeficiencyTypes::all(['id', 'description']);

    return response()->json($deficiencyTypes);
  }

  public function sendDeficiency()
  {
    $deficiency = Deficiency::all(['id', 'description']);

    return response()->json($deficiency);
  }

  public function store(Request $request)
  {
    if ($this->getUser($request)->is_institution) {
      try {
        $validatedData = $request->validate([
          'description' => ['required', 'string', 'max:75']
      ]);

      $newDeficiency = new Deficiency($validatedData);
      $newDeficiency->save();

      return response()->json(['message' => 'Novo campo adicionado com sucesso!']);
      } catch (\Exception $e) {
        return response()->json($e->getMessage(), 400);
      }
    } else {
      return response()->json(['error' => 'Usuário não autorizado!'], 401);
    }
  }

  public function delete(Request $request, $id)
  {
    if ($this->getUser($request)->is_institution) {
      try {
        $newDeficiency = Deficiency::find($id);

        $newDeficiency->delete();

        return response()->json(['message' => 'Campo excluído com sucesso!']);
      } catch (\Exception $e) {
        return response()->json($e->getMessage(), 400);
      }
    } else {
        return response()->json(['error' => 'Usuário não autorizado!'], 401);
    }
  }
}