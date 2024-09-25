<?php

namespace App\DashboardData;

use Illuminate\Support\Facades\DB;

class ReportRepository
{
  public static function getPublicDashReport($location = null)
  {
    $query = DB::table('pcd_users')
      ->select(
        'deficiency_types.description AS deficiency_type',
        'deficiency_types.id AS deficiency_type_id',
        DB::raw('COUNT(DISTINCT pcd_users.id) AS value')
      )
      ->join('pcd_user_deficiency', 'pcd_users.id', '=', 'pcd_user_deficiency.pcd_user_id')
      ->join('deficiency', 'pcd_user_deficiency.deficiency_id', '=', 'deficiency.id')
      ->join('deficiency_to_deficiency_types', 'deficiency.id', '=', 'deficiency_to_deficiency_types.deficiency_id')
      ->join('deficiency_types', 'deficiency_to_deficiency_types.deficiency_types_id', '=', 'deficiency_types.id');

    if ($location) {
      $query->where('pcd_users.neighborhood', $location);
    }

    return $query
      ->groupBy(
        'deficiency_types.description',
        'deficiency_types.id'
      )
      ->orderBy('deficiency_types.description', 'ASC')
      ->get();
  }
}
