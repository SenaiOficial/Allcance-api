<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\DashboardData\PcdsReport;
use App\Models\UserPcd;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class DashBoardController extends Controller
{
  private $pcdsReport;

  public function __construct(PcdsReport $pcdsReport)
  {
    $this->pcdsReport = $pcdsReport;
  }

  public function getPcdsReport()
  {
    try {
      $result = $this->pcdsReport->getReport();

      return response()->json($result);
    } catch (\Throwable $th) {
      Log::error($th);

      return response()->json([
        'success' => false,
        'errors' => 'Não foi possível completar a solicitação.'
      ], 500);
    }
  }

  public static function getLocations()
  {
    try {
      $response = Cache::remember('pcds_report', Carbon::now()->addDay(), function () {
        return UserPcd::query()
          ->select('neighborhood')
          ->distinct()
          ->get();
      });

      $neighborhoods = $response->pluck('neighborhood');

      return response()->json([
        'success' => true,
        'options' => $neighborhoods
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'error' => $e
      ]);
    }
  }
}