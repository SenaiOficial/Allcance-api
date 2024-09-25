<?php

namespace App\DashboardData;

use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class PcdsReport extends DashboardService
{
  protected $cacheTime;

  public function __construct()
  {
    $this->cacheTime = Carbon::now()->addDay();
  }

  public function getReport(string $location = null)
  {
    $cacheKey = 'dashboard-public-report-' . $location;

    $report = Cache::remember($cacheKey, $this->cacheTime, function () use ($location) {
      return ReportRepository::getPublicDashReport($location);
    });
    
    $totalCount = $report->sum('value');

    $results = $report->map(function ($item) use ($totalCount) {
      $item->percentage = $totalCount > 0 ? (int) floor(($item->value / $totalCount) * 100) : 0;
      return $item;
    });

    $groupedResults = $results->groupBy('deficiency_type');

    return $groupedResults;
  }
}