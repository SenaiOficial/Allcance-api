<?php

namespace App\Services;

use App\Models\Feeds;
use Carbon\Carbon;
use Dotenv\Exception\ValidationException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FeedsService
{
  protected $cacheFeeds = 'feeds-cache';
  protected $storage;
  protected $user;

  public function __construct()
  {
    $this->storage = env('STORAGE_URL');
    $this->user = auth('admin')->user();
  }

  private function queryPosts()
  {
    $users = Feeds::query()
      ->select(
        'admin_user_id',
        'admin_user.profile_photo',
        'admin_user.institution_name',
        DB::raw('count(*) as post_count')
      )
      ->join('admin_user', 'admin_user.id', '=', 'feeds.admin_user_id')
      ->groupBy('admin_user_id')
      ->orderByDesc('post_count')
      ->get();

    $posts = Feeds::all();

    return [
      'users' => $users,
      'posts' => $posts
    ];
  }

  public function get()
  {
    $posts = Cache::remember($this->cacheFeeds, Carbon::now()->addWeek(), function () {
      $query = $this->queryPosts();
      $posts = $query['posts'];
      $users = $query['users'];
      $arrPosts = [];
      $ranking = [];
      $position = 1;

      foreach ($users as $user) {
        $ranking[] = [
          'position' => $position++,
          'profile_photo' => $user->profile_photo,
          'user_name' => $user->institution_name,
        ];
      }

      foreach ($posts as $post) {
        $arrPosts[] = (object) array_merge($post->toArray(), [
          'image' => $post->image,
          'time' => 'Publicado ' . Carbon::parse($post->published_at)->format('d/m/Y \à\s H:i'),
          'cnpj' => $post->adminUser->cnpj
        ]);
      }

      return [
        'ranking' => $ranking,
        'posts' => $arrPosts
      ];
    });

    return response()->json($posts, 200, [], JSON_UNESCAPED_SLASHES);
  }

  public function store($request)
  {
    $user = $this->user;

    try {
      $request->validated();
      $fileImage = null;

      if ($request->hasFile('image')) {
        $fileImage = $request->file('image')->store('allcance', 's3');
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
          'image' => $fileImage,
          'published_at' => Carbon::now()
        ]);

      $this->cleanCacheFeeds();

      return response()->json([
        'success' => true,
        'message' => 'Post criado com sucesso!'
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'error' => $e->getMessage()
      ], 500);
    }
  }

  public function update($request, int $id)
  {
    $user = $this->user;
    $bucket = Storage::disk('s3');

    try {
      $data = $request->only([
        'is_event',
        'event_date',
        'event_time',
        'event_location',
        'title',
        'description',
        'image',
      ]);

      $post = Feeds::find($id);

      if (!$post) {
        return response()->json([
          'success' => false,
          'message' => 'Post não encontrado!'
        ], 404);
      }

      if ($post->admin_user_id !== $user->id) {
        return response()->json([
          'success' => false,
          'message' => 'Você não tem permissão para editar este post!'
        ], 401);
      }

      if ($request->hasFile('image')) {
        if ($bucket->exists($post->image)) {
          $bucket->delete($post->image);
        }

        $data['image'] = $request->file('image')->store('allcance', 's3');
      }

      $post->update($data);
      $this->cleanCacheFeeds();

      return response()->json([
        'success' => true,
        'message' => 'Post atualizado com sucesso!'
      ], 200);
    } catch (ValidationException $e) {
      return response()->json([
        'success' => false,
        'error' => $e->getMessage()
      ], 422);
    }
  }

  public function delete($request, $id)
  {
    $user = $this->user;

    try {
      if (!$id) {
        return response()->json([
          'success' => false,
          'message' => 'ID não informado!'
        ], 422);
      }

      if (!is_numeric($id)) {
        return response()->json([
          'success' => false,
          'message' => 'ID inválido!'
        ], 422);
      }

      $post = Feeds::query()
        ->select('id', 'admin_user_id')
        ->where('id', $id)
        ->first();

      if ($post->admin_user_id !== $user->id) {
        return response()->json([
          'success' => false,
          'message' => 'Você não tem permissão para deletar este post!'
        ], 401);
      }

      $post->delete();

      $this->cleanCacheFeeds();

      return response()->json([
        'success' => true,
        'message' => 'Post apagado com sucesso!'
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'error' => $e->getMessage()
      ], 500);
    }
  }

  public function cleanCacheFeeds()
  {
    Cache::forget($this->cacheFeeds);
  }

  private function unauthorized($user)
  {
    if (!$user->is_institution && !$user->is_admin) return abort(401, 'Unauthorized');
  }
}
