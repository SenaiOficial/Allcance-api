<?php

namespace App\Http\Controllers;

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
}
