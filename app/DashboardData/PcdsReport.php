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

    $report = config('app.env') !== 'production'
      ? $this->repository::getPublicDashReport($location)
      : Cache::remember($cacheKey, Carbon::now()->addDay(), function () use ($location) {
        return $this->repository::getPublicDashReport($location);
      });

    $count = $report->sum('value');

    $results = $report->map(function ($item) use ($count) {
      return [
        'deficiency_type' => $item->deficiency_type,
        'deficiency_type_id' => $item->deficiency_type_id,
        'deficiency' => $item->deficiency,
        'value' => $item->value,
        'percentage' => $count > 0 ? (int) floor(($item->value / $count) * 100) : 0
      ];
    });

    $result = $results->groupBy('deficiency_type')->map(function ($group, $type) {
      return [
        'type' => $type,
        'type_id' => $group->first()['deficiency_type_id'],
        'conditions' => $group->map(function ($item) {
          return [
            'name' => $item['deficiency'],
            'value' => $item['value'],
            'percentage' => $item['percentage']
          ];
        })->values()->toArray()
      ];
    })->values()->toArray();

    return $result;
  }
}
