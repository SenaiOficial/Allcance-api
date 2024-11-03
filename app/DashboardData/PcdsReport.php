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

  public function getReport(string $state = null, string $neighborhood = null, string $type_pcd = null): mixed
  {
    $cacheKey = 'dashboard-public-report-' . $state . '-' . $neighborhood . '-' . $type_pcd;

    $report = config('app.env') !== 'production'
      ? $this->repository::getPublicDashReport($state, $neighborhood, $type_pcd)
      : Cache::remember($cacheKey, Carbon::now()->addDay(), function () use ($state, $neighborhood, $type_pcd) {
        return $this->repository::getPublicDashReport($state, $neighborhood, $type_pcd);
      });

    $results = $report->map(function ($item) {
      return [
        'deficiency_type' => $item->deficiency_type,
        'deficiency_type_id' => $item->deficiency_type_id,
        'deficiency' => $item->deficiency,
        'value' => $item->value
      ];
    });

    $result = $results->groupBy('deficiency_type')->map(function ($group, $type) {
      $totalValue = $group->sum('value');

      $conditions = $group->map(function ($item) use ($totalValue) {
        return [
          'name' => $item['deficiency'],
          'value' => $item['value'],
          'percentage' => $totalValue > 0 ? round(($item['value'] / $totalValue) * 100, 2) : 0
        ];
      })->values()->toArray();

      return [
        'type' => $type,
        'type_id' => $group->first()['deficiency_type_id'],
        'conditions' => $conditions,
      ];
    })->values()->toArray();

    if (count($result) > 1) {
        usort($result, function ($a, $b) {
            return $a['type_id'] <=> $b['type_id'];
        });
    }

    return $result;
  }

  public function getLocationsOptions(string $state = null, string $city = null): mixed
  {
    $cacheKey = 'neighborhoods-' . $state . '-' . $city;

    $result = config('app.env') !== 'production'
      ? $this->repository::getLocationsOptions($state, $city)
      : Cache::remember($cacheKey, Carbon::now()->addDay(), function () use ($state, $city) {
        return $this->repository::getLocationsOptions($state, $city);
      });

      $groupedNeighborhoods = $result->groupBy('pcd_type')->map(function ($group, $key) {
        $formattedNeighborhoods = $group->pluck('neighborhood')->map(function ($neighborhood) {
            return ['id' => null, 'value' => $neighborhood];
        });

        $formattedNeighborhoods->prepend(['id' => 1, 'value' => 'Valores Totais']);

        return $formattedNeighborhoods->map(function ($item, $index) use ($key) {
            return ['id' => $index +1, 'value' => $item['value']];
        });
    });

    $formattedOptions = $groupedNeighborhoods->toArray();

    return $groupedNeighborhoods;
  }

}
