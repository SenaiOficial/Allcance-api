<?php

namespace App\Http\Controllers;

use App\DashboardData\PcdsReport;
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
}