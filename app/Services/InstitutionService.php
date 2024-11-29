<?php

namespace App\Services;

use App\Models\Feeds;
use App\Services\User\SuperAdminService;
use App\Models\UserAdmin;
use Carbon\Carbon;

class InstitutionService extends SuperAdminService
{
  protected $user;

  public function __construct()
  {
    $this->user = auth('admin')->user();
  }

  public function get()
  {
    $request = UserAdmin::select(
      'institution_name',
      'cnpj'
    )->get();

    $name = $request->pluck('institution_name');
    $cnpj = $request->pluck('cnpj');

    return response()->json([
      'success' => true,
      'name' => $name,
      'cnpj' => $cnpj
    ], 200);
  }

  public function getInstitutions($param)
  {
    $request = UserAdmin::query()
      ->orWhere('institution_name', $param)
      ->orWhere('cnpj', $param)
      ->first();

    return response()->json([
      'success' => true,
      'institution' => $request
    ], 200);
  }

  public function getPostsByCnpj($cnpj)
  {
    try {
      $posts = $this->getPosts($cnpj);
      $formatedPosts = [];

      foreach ($posts as $post) {
        $formatedPosts[] = (object) array_merge($post->toArray(), [
          'image' => $post->image,
          'time' => 'Publicado ' . Carbon::parse($post->published_at)->format('d/m/Y \à\s H:i'),
          'cnpj' => $cnpj
        ]);
      }

      if (empty($formatedPosts) || !$formatedPosts) {
        return response()->json([
          'message' => 'Nenhum post encontrado!'
        ], 404);
      }

      return response()->json([
        'success' => true,
        'posts' => $formatedPosts
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => $e->getMessage()
      ], 500);
    }
  }

  public function blockInstitution($request)
  {
    return $this->blockInstitution($request);
  }

  private function getPosts($cnpj)
  {
    return Feeds::query()
      ->select(
        'feeds.id',
        'title',
        'event_location',
        'is_event',
        'description',
        'image',
        'event_date',
        'event_time',
        'admin_user.institution_name'
      )
      ->join('admin_user', 'feeds.admin_user_id', '=', 'admin_user.id')
      ->where('admin_user.cnpj', $cnpj)
      ->get();
  }
}
