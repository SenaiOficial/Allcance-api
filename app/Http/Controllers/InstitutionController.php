<?php

namespace App\Http\Controllers;

use App\Models\UserAdmin;

class InstitutionController extends Controller
{
  public function get()
  {
    $request = UserAdmin::select('institution_name', 'cnpj')->get();

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
    $request = UserAdmin::where('institution_name', '=', $param)
    ->orWhere('cnpj', '=', $param)
    ->first();

    return response()->json([
      'success' => true,
      'institution' => $request
    ], 200);
  }
}
