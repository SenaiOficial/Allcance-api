<?php

namespace App\Http\Controllers;

use App\Models\UserAdmin;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class InstitutionController extends Controller
{
  protected $user;

  public function __construct()
  {
    $this->user = auth('admin')->user();
  }
  public function get()
  {
    $request = UserAdmin::select(
      'institution_name',
      'cnpj'
    )->get();

    $name = $request->pluck('institution_name');
    $cnpj = $request->pluck('cnpj');

    return response()->json([
      'success' => true,
      'name' => $name,
      'cnpj' => $cnpj
    ], 200);
  }

  public function getInstitutions($param)
  {
    $request = UserAdmin::query()
      ->orWhere('institution_name', '=', $param)
      ->orWhere('cnpj', '=', $param)
      ->first();

    return response()->json([
      'success' => true,
      'institution' => $request
    ], 200);
  }

  public function blockInstitution(Request $request)
  {
    $user = $this->user;

    try {
      $request->validate([
        'id' => 'required|integer',
        'password' => 'required'
      ]);

      if (!Hash::check($request->password, $user->password)) {
        return response('Senha incorreta!', 401);
      }

      $updated = UserAdmin::where('id', $request->id)
        ->where('is_institution', true)
        ->where('is_admin', false)
        ->update([
          'is_blocked' => DB::raw('NOT is_blocked')
        ]);

      if ($updated) {
        $institution = UserAdmin::find($request->id);
        $message = 'Instituição bloqueada!';

        if (!$institution->is_blocked) $message = 'Instituição desbloqueada!';
      } else {
        return response('Instituição não encontrada', 404);
      }

      return response()->json([
        'success' => true,
        'message' => $message
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Erro' . $e->getMessage()
      ], 500);
    }
  }
}
