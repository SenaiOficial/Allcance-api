<?php

namespace App\Services;

use App\Models\Feeds;
use App\Models\UserAdmin;
use Carbon\Carbon;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FeedsService
{
  protected $cacheFeeds = 'feeds-cache';
  protected $cacheRanking = 'rank-cache';
  protected $storage;
  protected $user;

  public function __construct()
  {
    $this->storage = env('STORAGE_URL');
    $this->user = auth('admin')->user();
  }

  protected function posts()
  {
    return Feeds::all();
  }

  public function get()
  {
    $formattedPosts = Cache::remember($this->cacheFeeds, Carbon::now()->addWeek(), function () {
      $posts = $this->posts();
      $formattedPosts = [];

      foreach ($posts as $post) {
        $published = Carbon::parse($post->published_at)->format('d/m/Y \à\s H:i');
        $image = $this->storage . $post->image;
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
    $user = $this->user;
    $this->unauthorized($user);
    try {
      $request->validated();
      $image = null;

      if ($request->hasFile('image')) {
        $image = Storage::disk('public')->put('images', $request->file('image'));
      }

      Feeds::query()
        ->create([
          'admin_user_id' => $user->id,
          'profile_photo' => $user->profile_photo,
          'institution_name' => $user->institution_name,
          'is_event' => $request->is_event,
          'event_date' => $request->event_date,
          'event_time' => $request->event_time,
          'event_location' => $request->event_location,
          'title' => $request->title,
          'description' => $request->description,
          'image' => 'storage/' . $image,
          'published_at' => Carbon::now()
        ]);

      $this->cleanCacheFeeds();

      return response()->json(['message' => 'Post criado com sucesso!'], 200);
    } catch (\Exception $e) {
      return response()->json([$e->getMessage()], 400);
    }
  }

  public function update($request, $id)
  {
    $user = $this->user;

    $this->unauthorized($user);

    try {
      $data = $request->validate([
        'is_event',
        'event_date',
        'event_time',
        'event_location',
        'title',
        'description',
        'image',
      ]);

      $feedQuery = Feeds::where('id', $id);

      if (!$user->is_admin) {
        $feedQuery->where('admin_user_id', $user->id);
      }

      $feed = $feedQuery->firstOrFail();

      $feed->update($data);
      return response()->json(['message' => 'Dados atualizados com sucesso!'], 200);
    } catch (ValidationException $e) {
      return response()->json(['error' => $e->errors()], 422);
    }
  }

  public function delete(Request $request, $id)
  {
    $user = $this->user;

    $this->unauthorized($user);

    $post = Feeds::find($id);
    $post->delete();

    $this->cleanCacheFeeds();

    return response()->json(['message' => 'Post apagado com sucesso!'], 200);
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

  public function getPostByInstitution($institution)
  {
    return Feeds::all()->where('institution_name', '=', $institution);
  }

  private function unauthorized($user)
  {
    if (!$user->is_institution && !$user->is_admin)
      return abort(401, 'Unauthorized');
  }
}