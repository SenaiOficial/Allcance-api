<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\DashboardData\PcdsReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class DashBoardController extends Controller
{
  private $pcdsReport;

  public function __construct(PcdsReport $pcdsReport)
  {
    $this->pcdsReport = $pcdsReport;
  }

  public function getPcdsReport(Request $request)
  {
    try {
      $state = $request->state;
      $city = $request->city;
      $neighborhood = $request->neighborhood;
      $type_pcd = $request->type_pcd;

      $result = $this->pcdsReport->getReport($state, $city, $neighborhood, $type_pcd);

      return response()->json($result);
    } catch (\Throwable $th) {
      Log::error($th);

      return response()->json([
        'success' => false,
        'errors' => 'Não foi possível completar a solicitação.'
      ], 500);
    }
  }

  public function getLocations(Request $request)
  {
    try {
      $state = $request->state;
      $city = $request->city;

      $result = $this->pcdsReport->getLocationsOptions($state, $city);

      return response()->json([
        'success' => true,
        'options' => $result
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'error' => $e
      ]);
    }
  }
}
