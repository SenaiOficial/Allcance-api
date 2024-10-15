<?php

namespace App\Services;

use App\Services\User\SuperAdminService;
use App\Models\UserAdmin;

class InstitutionService extends SuperAdminService
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
      ->orWhere('institution_name', $param)
      ->orWhere('cnpj', $param)
      ->first();

    return response()->json([
      'success' => true,
      'institution' => $request
    ], 200);
  }

  public function blockInstitution($request)
  {
    return $this->blockInstitution($request);
  }
}
