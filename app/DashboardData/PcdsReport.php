<?php

namespace App\DashboardData;

use App\Models\UserPcd;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;


class PcdsReport extends DashboardService
{
  public function getReport()
  {
    $report = Cache::remember('pcds_report', Carbon::now()->addDays(2), function () {
      $totalByNeighborhood = UserPcd::select('neighborhood', \DB::raw('COUNT(*) as total'))
        ->groupBy('neighborhood')
        ->get()
        ->keyBy('neighborhood')
        ->toArray();

      $pcdsByNeighborhood = UserPcd::select('neighborhood', 'pcd')
        ->get()
        ->groupBy('neighborhood')
        ->map(function ($group) {
          return $group->groupBy('pcd')->map->count();
        });

      return $this->calculate($totalByNeighborhood, $pcdsByNeighborhood);
    });

    return $report;
  }

  private function calculate($totalByNeighborhood, $pcdsByNeighborhood)
  {
    $percentageByNeighborhood = [];
    foreach ($pcdsByNeighborhood as $neighborhood => $pcds) {
      $total = $totalByNeighborhood[$neighborhood]['total'] ?? 1;
      $percentages = [];
      foreach ($pcds as $pcd => $count) {
        $percentage = ($count / $total) * 100;
        $percentages[$pcd] = intval($percentage);
      }
      $percentageByNeighborhood[$neighborhood] = $percentages;
    }

    return $percentageByNeighborhood;
  }
}