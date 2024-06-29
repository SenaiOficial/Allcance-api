<?php

namespace App\DashboardData;

use App\Models\UserPcd;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class PcdsReport extends DashboardService
{
  public function getReport()
  {
    // fazer o value nao repitir caso o id de usuario for repitido 
    $report = Cache::remember('pcds_report', Carbon::now()->addDay(), function () {
      $pcdsByNeighborhood = UserPcd::select('neighborhood', 'deficiency_types.description')
        ->join('pcd_user_deficiency', 'pcd_users.id', '=', 'pcd_user_deficiency.pcd_user_id')
        ->join('deficiency_types', 'pcd_user_deficiency.deficiency_types_id', '=', 'deficiency_types.id')
        ->get()
        ->groupBy('neighborhood')
        ->map(function ($group) {
          return $group->pluck('description')->countBy();
        });

      return $this->calculate($pcdsByNeighborhood);
    });
    Cache::forget('pcds_report');

    return $report;
  }

  private function calculate($pcdsByNeighborhood)
  {
    $reportData = [];

    foreach ($pcdsByNeighborhood as $neighborhood => $pcds) {
      $totalCount = array_sum($pcds->all());
      $data = [];

      foreach ($pcds as $pcd => $count) {
        $percentage = ($count / $totalCount) * 100;

        $data[] = [
          'name' => $pcd,
          'value' => $count,
          'percentage' => intval($percentage),
        ];
      }

      $reportData[] = [
        'title' => $neighborhood,
        'data' => $data,
      ];
    }

    return $reportData;
  }
}