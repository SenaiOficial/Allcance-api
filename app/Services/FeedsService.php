<?php

namespace App\Services;

use App\Models\Feeds;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Support\Facades\Cache;

class FeedsService
{
  protected $userService;
  protected $cache = 'feeds-cache';

  public function __construct(UserService $userService)
  {
    $this->userService = $userService;
  }

  private function user(Request $request)
  {
    $bearer = $request->bearerToken();

    return $this->userService->findUserByToken($bearer);
  }

  protected function posts()
  {
    return Feeds::select(
      'profile_photo',
      'institution_name',
      'is_event',
      'event_date',
      'event_time',
      'event_location',
      'title',
      'description',
      'image',
      'published_at'
    )->get();
  }

  public function get()
  {
    $formattedPosts = Cache::remember($this->cache, Carbon::now()->addWeek(), function () {
      $posts = $this->posts();
      $formattedPosts = [];

      foreach ($posts as $post) {
        $published = Carbon::parse($post->published_at)->format('d/m/Y \à\s H:i');
        $formattedPosts[] = [
          'post' => $post,
          'time' => 'Publicado ' . $published
        ];
      }

      return $formattedPosts;
    });

    return response()->json($formattedPosts, 200, [], JSON_UNESCAPED_SLASHES);
  }

  public function store($request)
  {
    $user = $this->user($request);

    if ($user->is_institution) {
      try {
        $validateData = $request->validated();
        $validateData['admin_user_id'] = $user->id;
        $validateData['profile_photo'] = $user->profile_photo;
        $validateData['institution_name'] = $user->institution_name;
        $validateData['published_at'] = Carbon::now();

        $feed = Feeds::create($validateData);
        $feed->save();

        Cache::forget($this->cache);

        return response()->json(['message' => 'Banner criado com sucesso!'], 200);
      } catch (\Exception $e) {
        return response()->json([$e->getMessage()], 400);
      }
    } else {
      return response()->json(['error' => 'Usuário não autorizado!'], 401);
    }
  }

  public function delete(Request $request, $id)
  {
    $user = $this->user($request);

    if ($user->is_institution) {
      $post = Feeds::find($id);

      $post->delete();

      Cache::forget($this->cache);

      return response()->json(['message' => 'Post apagado com sucesso!'], 200);
    } else {
      return response()->json(['error' => 'Usuário não autorizado!'], 401);
    }
  }
}