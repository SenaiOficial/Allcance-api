<?php

namespace App\DashboardData;

use App\Models\UserPcd;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PcdsReport extends DashboardService
{
  protected $cacheKey = 'pcds_report';
  protected $cacheTime;

  public function __construct()
  {
    $this->cacheTime = Carbon::now()->addDay();
  }

  public function getReport(string $location = null): array
  {
    $cachedData = Cache::get($this->cacheKey);

    if (!$cachedData) {
      $pcdsByNeighborhood = UserPcd::select('neighborhood', 'deficiency_types.description',
        DB::raw('COUNT(DISTINCT pcd_users.id) as count'))
        ->join('pcd_user_deficiency', 'pcd_users.id', '=', 'pcd_user_deficiency.pcd_user_id')
        ->join('deficiency_types', 'pcd_user_deficiency.deficiency_types_id', '=', 'deficiency_types.id')
        ->groupBy('neighborhood', 'deficiency_types.description')
        ->orderBy('neighborhood')
        ->orderBy('description')
        ->get()
        ->groupBy('neighborhood')
        ->map(function ($group) {
          return $group->pluck('count', 'description');
        });

      $cachedData = $this->calculate($pcdsByNeighborhood);
      Cache::put($this->cacheKey, $cachedData, $this->cacheTime);
    }

    if (isset($location)) {
      $cachedData = array_filter($cachedData, function ($item) use ($location) {
        return stripos($item['title'], $location) !== false;
      });
    } else {
      $cachedData = $this->calculateTotal($cachedData);
    }

    return $cachedData;
  }

  private function calculate($pcdsByNeighborhood): array
  {
    $reportData = [];

    foreach ($pcdsByNeighborhood as $neighborhood => $pcds) {
      $totalCount = array_sum($pcds->all());
      $data = [];

      foreach ($pcds as $pcd => $count) {
        $percentage = ($count / $totalCount) * 100;

        $data[] = [
          'name' => $pcd,
          'percentage' => intval($percentage),
          'value' => $count,
        ];
      }

      $reportData[] = [
        'title' => $neighborhood,
        'data' => $data,
      ];
    }

    return $reportData;
  }

  private function calculateTotal($reportData): array
  {
    $totalCounts = [];
    $totalSum = 0;

    foreach ($reportData as $report) {
      foreach ($report['data'] as $data) {
        if (isset($totalCounts[$data['name']])) {
          $totalCounts[$data['name']] += $data['value'];
        } else {
          $totalCounts[$data['name']] = $data['value'];
        }
        $totalSum += $data['value'];
      }
    }

    $totalData = [];
    foreach ($totalCounts as $name => $count) {
      $percentage = ($count / $totalSum) * 100;
      $totalData[] = [
        'name' => $name,
        'percentage' => intval($percentage),
        'value' => $count,
      ];
    }

    return [
      [
        'title' => 'Valores Total',
        'data' => $totalData,
      ]
    ];
  }
}
