<?php

namespace App\Http\Controllers;

use App\Models\UserAdmin;
use App\Services\InstitutionService;
use Illuminate\Http\Request;

class InstitutionController extends Controller
{
  protected $institutionService;

  public function __construct(InstitutionService $institutionService)
  {
    $this->institutionService = $institutionService;
  }

  public function get()
  {
    return $this->institutionService->get();
  }

  public function getInstitutions($param)
  {
    return $this->institutionService->getInstitutions($param);
  }

  public function blockInstitution(Request $request)
  {
    return $this->institutionService->block($request);
  }

  public function getPostsByCnpj($cnpj)
  {
    if (!$this->verifyExistsCNPJ($cnpj)) {
      return response()->json(['message' => 'CNPJ não cadastrado em nossa base!'], 404);
    }

    return $this->institutionService->getPostsByCnpj($cnpj);
  }

  private function verifyExistsCNPJ($cnpj): bool
  {
    return UserAdmin::query()
      ->where('cnpj', $cnpj)
      ->exists();
  }
}
