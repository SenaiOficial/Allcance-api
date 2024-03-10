<?php

namespace App\Http\Controllers;

use App\Services\DeficiencyService;
use Illuminate\Http\Request;

class DeficiencyController extends Controller
{
  protected $deficiencyService;

  public function __construct(DeficiencyService $deficiencyService)
  {
    $this->deficiencyService = $deficiencyService;
  }

  public function getTypes()
  {
    return $this->deficiencyService->sendDeficiencyTypes();
  }

  public function get()
  {
    return $this->deficiencyService->sendDeficiency();
  }

  public function store(Request $request)
  {
    return $this->deficiencyService->store($request);
  }

  public function delete(Request $request, $id)
  {
    return $this->deficiencyService->delete($request, $id);
  }
}