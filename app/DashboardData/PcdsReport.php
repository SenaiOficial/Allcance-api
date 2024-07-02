<?php

namespace App\DashboardData;

use App\Models\UserPcd;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PcdsReport extends DashboardService
{
  public function getReport(string $location = null): array
  {
    $report = Cache::remember('pcds_report', Carbon::now()->addDay(), function () use ($location) {
      $pcdsByNeighborhood = UserPcd::select('neighborhood', 'deficiency_types.description', DB::raw('COUNT(DISTINCT pcd_users.id) as count'))
        ->join('pcd_user_deficiency', 'pcd_users.id', '=', 'pcd_user_deficiency.pcd_user_id')
        ->join('deficiency_types', 'pcd_user_deficiency.deficiency_types_id', '=', 'deficiency_types.id')
        ->when($location, function ($query, $location) {
          return $query->where('pcd_users.neighborhood', $location);
        })
        ->groupBy('neighborhood', 'deficiency_types.description')
        ->orderBy('neighborhood')
        ->orderBy('description')
        ->get()
        ->groupBy('neighborhood')
        ->map(function ($group) {
          return $group->pluck('count', 'description');
        });

      // dd($pcdsByNeighborhood);
      return $this->calculate($pcdsByNeighborhood, $location);
    });

    Cache::forget('pcds_report');

    return $report;
  }

  private function calculate($pcdsByNeighborhood, $location): array
  {
    $reportData = [];

    foreach ($pcdsByNeighborhood as $neighborhood => $pcds) {
      if ($location) {
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
      } else {
        $totalCount = array_sum($pcds->all());
        foreach ($pcdsByNeighborhood as $pcd => $count) {

          dd($count);
          $percentage = ($count / $totalCount) * 100;

          $reportData[] = [
            'name' => $pcd,
            'percentage' => intval($percentage),
            'value' => $count,
          ];
        }
      }
    }

    return $reportData;
  }
}