<?php

namespace App\DashboardData;

use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class PcdsReport extends DashboardService
{
  private $repository;

  public function __construct(ReportRepository $repository)
  {
    $this->repository = $repository;
  }

  public function getReport(string $location = null): mixed
  {
    $cacheKey = 'dashboard-public-report-' . $location;

    if (config('app.env') !== 'production') {
      $report = $this->repository::getPublicDashReport($location);
    } else {
      $report = Cache::remember($cacheKey, Carbon::now()->addDay(), function () use ($location) {
        return $this->repository::getPublicDashReport($location);
      });
    }

    $count = $report->sum('value');

    $results = $report->map(function ($item) use ($count) {
      $item->percentage = $count > 0 ? (int) floor(($item->value / $count) * 100) : 0;
      return $item;
    });

    return $results->groupBy('deficiency_type');
  }
}