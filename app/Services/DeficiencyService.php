<?php

namespace App\Services;

use App\Models\Deficiency;
use App\Models\DeficiencyTypes;
use App\Models\UserPcd;
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
    $this->checkIsAdmin();

    try {
      $validatedData = $request->validate([
        'description' => 'required|string|max:75',
        'deficiency_types_id' => 'required|integer'
      ]);

      $newDeficiency = new Deficiency($validatedData);
      $newDeficiency->save();

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
    $this->checkIsAdmin();

    try {
      $requestData = $request->only(['description']);

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
    $this->checkIsAdmin();

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

      $user = DB::table('pcd_users')
        ->select('id')
        ->join('pcd_user_deficiency', 'pcd_users.id', '=', 'pcd_user_deficiency.pcd_user_id')
        ->where('pcd_user_deficiency.deficiency_id', '=', $request->id)
        ->first();

      $timestamp = DB::table('deficiency')
        ->select('created_at')
        ->where('id', $request->id)
        ->first();

      // dd($timestamp);

      $expires_in = Carbon::parse($timestamp)->addMinutes(5);

      if (isset($user) || $expires_in <= Carbon::now() || !$expires_in) {
        return response([
          'success' => false,
          'message' => 'Não pode mais apagar este campo, contate um administrador'
        ], 400);
      }

      // $newDeficiency = Deficiency::find($request->id);
      // $newDeficiency->delete();

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

  private function checkIsAdmin()
  {
    if (!$this->admin->is_admin) return abort(401, 'Unauthorized');

    return null;
  }
}