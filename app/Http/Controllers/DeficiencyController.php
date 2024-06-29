<?php

namespace App\Http\Controllers;

use App\Models\Deficiency;
use App\Models\DeficiencyTypes;
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
    $deficiencyTypes = DeficiencyTypes::all(['id', 'description']);

    return response()->json($deficiencyTypes);
  }
  
  public function get()
  {
    $deficiency = Deficiency::all(['id', 'description']);

    return response()->json($deficiency);
  }

  public function store(Request $request)
  {
    return $this->deficiencyService->store($request);
  }

  public function delete(Request $request)
  {
    return $this->deficiencyService->delete($request);
  }

  public function getDeficiencies()
  {
    $user = auth('api')->user();

    return response()->json([
      'deficiency_type' => $user->deficiencyTypes->description,
      'deficiency' => $user->pcdDeficiencies->map->deficiency
    ]);
  }
}