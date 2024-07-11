<?php

namespace App\Services;

use App\Models\Deficiency;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeficiencyService
{
  protected $admin;

  public function __construct()
  {
    $this->admin = auth('admin')->user();
  }

  public function store(Request $request)
  {
    try {
      $request->validate([
        'description' => 'required|string|max:75',
        'deficiency_types_id' => 'required|integer',
        'password' => 'required'
      ]);

      if (checkUserPassword($request->password, $this->admin->password)) {
        return response()->json([
          'success' => false,
          'message' => 'Senha incorreta!'
        ], 401);
      }

      Deficiency::query()
        ->create([
          'description' => $request->description,
          'deficiency_types_id' => $request->deficiency_types_id
        ]);

      return response()->json([
        'success' => true,
        'message' => 'Novo campo adicionado com sucesso!'
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'erro' => $e->getMessage()
      ], 500);
    }
  }

  public function update(Request $request, $id)
  {
    try {
      $requestData = $request
        ->only(['description']);

      if ($this->checkIsValidId($id)) {
        return response([
          'success' => false,
          'message' => 'Não pode mais alterar este campo, contate um administrador!'
        ], 400);
      };

      Deficiency::find($id)
        ->update($requestData);

      return response()->json([
        'success' => true,
        'message' => 'Campo atualizado com sucesso!'
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'erro' => $e->getMessage()
      ], 500);
    }
  }

  public function delete(Request $request)
  {
    try {
      $request->validate([
        'id' => 'required|integer',
        'password' => 'required'
      ]);

      if (checkUserPassword($request->password, $this->admin->password)) {
        return response()->json([
          'success' => false,
          'message' => 'Senha incorreta!'
        ], 401);
      }

      if ($this->checkIsValidId($request->id)) {
          return response([
          'success' => false,
          'message' => 'Não pode mais alterar este campo, contate um administrador!'
        ], 400);
      };

      Deficiency::destroy($request->id);

      return response()->json([
        'success' => true,
        'message' => 'Campo excluído com sucesso!'
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'erro' => $e->getMessage()
      ], 500);
    }
  }

  private function checkIsValidId($id)
  {
    try {
      $user = DB::table('pcd_users')
      ->select('id')
      ->join('pcd_user_deficiency', 'pcd_users.id', '=', 'pcd_user_deficiency.pcd_user_id')
      ->where('pcd_user_deficiency.deficiency_id', $id)
      ->first();

      $timestamp = DB::table('deficiency')
        ->select('created_at')
        ->where('id', $id)
        ->first();

      $expires_in = Carbon::parse($timestamp->created_at)->addMinutes(10);

      if (isset($user) || $expires_in <= Carbon::now() || !$expires_in) return true;

      return false;
    } catch (\Exception $e) {
      return true;
    }
  }
}
