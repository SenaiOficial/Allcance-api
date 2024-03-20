<?php

namespace App\Services;

use App\Models\Feeds;
use App\Models\UserAdmin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FeedsService
{
  protected $userService;
  protected $cacheFeeds = 'feeds-cache';
  protected $cacheRanking = 'rank-cache';
  protected $api_url;
  protected $storage;

  public function __construct(UserService $userService)
  {
    $this->userService = $userService;
    $this->api_url = env('APP_PRODUCTION_URL');
    $this->storage = env('STORAGE_URL');
  }

  private function user(Request $request)
  {
    $bearer = $request->bearerToken();

    return $this->userService->findUserByToken($bearer);
  }

  protected function posts()
  {
    return Feeds::select(
      'id',
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
    $formattedPosts = Cache::remember($this->cacheFeeds, Carbon::now()->addWeek(), function () {
      $posts = $this->posts();
      $formattedPosts = [];

      foreach ($posts as $post) {
        $published = Carbon::parse($post->published_at)->format('d/m/Y \à\s H:i');
        $image = $this->api_url . $this->storage . $post->image;
        $formattedPosts[] = [
          'post' => $post,
          'image' => $image,
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
        $image = $request->file('image');
        $filename = $image->hashName();
        Storage::disk('public')->put('images', $image);
        $validateData['admin_user_id'] = $user->id;
        $validateData['profile_photo'] = $user->profile_photo;
        $validateData['institution_name'] = $user->institution_name;
        $validateData['published_at'] = Carbon::now();
        $validateData['image'] = $filename;

        $feed = Feeds::create($validateData);
        $feed->save();

        $this->cleanCacheFeeds();

        return response()->json(['message' => 'Post criado com sucesso!'], 200);
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

      $this->cleanCacheFeeds();

      return response()->json(['message' => 'Post apagado com sucesso!'], 200);
    } else {
      return response()->json(['error' => 'Usuário não autorizado!'], 401);
    }
  }

  public function getHighlightsInstitutions()
  {
    $institutions = Cache::remember($this->cacheRanking, Carbon::now()->addWeek(), function () {
      $institutionsUsers = Feeds::select(
        'admin_user_id',
        DB::raw('count(*) as post_count'),
        DB::raw('@rank := @rank + 1 as position')
      )->join(DB::raw('(SELECT @rank := 0) as r'), function ($join) {
        $join->on(DB::raw('1'), DB::raw('1'));
      })
        ->groupBy('admin_user_id')
        ->orderByDesc('post_count')
        ->get();

      $institutions = [];
      foreach ($institutionsUsers as $institutionsUser) {
        $user = UserAdmin::find($institutionsUser->admin_user_id);
        $institutions[] = [
          'position' => $institutionsUser->position,
          'profile_photo' => $user->profile_photo,
          'user_name' => $user->institution_name,
        ];
      }

      return $institutions;
    });

    return response()->json($institutions, 200);
  }

  protected function cleanCacheFeeds()
  {
    Cache::forget($this->cacheFeeds);
    Cache::forget($this->cacheRanking);
  }
}