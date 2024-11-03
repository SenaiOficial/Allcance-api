<?php

namespace App\DashboardData;

use Illuminate\Support\Facades\DB;

class ReportRepository
{
  public static function getPublicDashReport($state = null, $neighborhood = null, $type_pcd = null)
  {
    $query = DB::table('pcd_users')
      ->select(
        'deficiency_types.description AS deficiency_type',
        'deficiency_types.id AS deficiency_type_id',
        'deficiency.description AS deficiency',
        DB::raw('COUNT(DISTINCT pcd_users.id) AS value')
      )
      ->join('pcd_user_deficiency', 'pcd_users.id', '=', 'pcd_user_deficiency.pcd_user_id')
      ->join('deficiency', 'pcd_user_deficiency.deficiency_id', '=', 'deficiency.id')
      ->join('deficiency_to_deficiency_types', 'deficiency.id', '=', 'deficiency_to_deficiency_types.deficiency_id')
      ->join('deficiency_types', 'deficiency_to_deficiency_types.deficiency_types_id', '=', 'deficiency_types.id');

      if ($state) {
        $query->where('pcd_users.state', $state);
      }

      if ($neighborhood) {
        $query->where('pcd_users.neighborhood', $neighborhood);
      }

      if ($type_pcd) {
        $query->where('pcd_users.pcd_type', $type_pcd);
      }

    return $query
      ->groupBy(
        'deficiency_types.description',
        'deficiency_types.id',
        'deficiency.description'
      )
      ->orderBy('deficiency_types.description', 'ASC')
      ->get();
  }

    public static function getLocationsOptions($state = null, $city = null)
    {
        $query = DB::table('pcd_users')
        ->select('neighborhood', 'pcd_type')
        ->distinct();

        if ($state) {
            $query->where('state', $state);
        }

        if ($city) {
            $query->where('city', $city);
        }

        return $query->get();
    }

}
