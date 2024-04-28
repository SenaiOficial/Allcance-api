<?php

namespace App\Services;

use App\Models\Deficiency;
use App\Models\DeficiencyTypes;
use Illuminate\Http\Request;

class DeficiencyService
{
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
    $user = auth('admin')->user();
    //super admin
    if ($user->is_admin) {
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

    $user = auth('admin')->user();
    //super admin
    if ($user->is_admin) {
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