<?php

namespace App\Services;

use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use App\Models\Suggestions;

class SuggestionsService
{
  protected $user;

  public function __construct()
  {
    $this->user = auth(getActiveGuard())->user();
  }

  public function store(Request $request)
  {
    $user = $this->user;
    $type = $user->getTable();

    try {
      if ($this->validateTimePost($type, $user->id)) {
        return response()->json([
          'success' => false,
          'message' => 'Você já fez uma postagem hoje, tente novamente outro dia.'
        ], 400);
      }

      $content = $request->validate(['content' => 'required|string|max:1000']);

      Suggestions::create([
        'content' => $content['content'],
        'user_id' => $user->id,
        'type' => $type,
        'user' => $user->first_name ?? $user->institution_name
      ]);

      return response()->json([
        'success' => true,
        'message' => 'Sua sugestão foi enviada com sucesso!'
      ]);
    } catch (\Exception $e) {
      return response()->json($e->getMessage(), 500);
    }
  }

  public function update($id)
  {
    try {
      $suggestion = Suggestions::find($id);
      
      if (!$suggestion) return response('Sugestão não encontrada!', 404);
      
      $suggestion->update(['approved' => true]);

      return response()->json([
        'success' => true,
        'message' => 'Sugestão atualizada com sucesso!'
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'erro' => $e->getMessage()
      ], 500);
    }
  }

  public function delete($id)
  {
    try {
      $suggestion = Suggestions::find($id);

      if (!$suggestion) return response('Sugestão não encontrada!', 404);

      $suggestion->delete();

      return response()->json([
        'success' => true,
        'message' => 'Sugestão excluída com sucesso!'
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'erro' => $e->getMessage()
      ], 500);
    }
  }

  private function showSuggestions($approved)
  {
    try {
      $suggestions = Suggestions::query()
        ->where('approved', $approved)
        ->get();

        foreach ($suggestions as $suggestion) {
          $formattedSuggestions[] = [
          'id' => $suggestion->id,
          'user' => $suggestion->user,
          'content' => $suggestion->content,
          'created_at' => Carbon::parse($suggestion->created_at)->format('d/m/Y')
        ];
      }

      if (!isset($suggestions)) {
        return response()->json([
          'success' => false,
          'message' => 'Nenhuma sugestão encontrada'
        ], 404);
      }

      return response()->json([
        'success' => true,
        'suggestions' => $formattedSuggestions
      ]);
    } catch (\Exception $e) {
      return response()->json($e->getMessage(), 500);
    }
  }

  public function showApproved()
  {
    return $this->showSuggestions(true);
  }

  public function showSuggestionsReq()
  {
    return $this->showSuggestions(false);
  }

  private function validateTimePost(string $type, int $user_id): bool
  {
    $post = DB::table('suggestions')
      ->where('user_id', '=', $user_id)
      ->where('type', '=', $type)
      ->whereDate('created_at', Carbon::today())
      ->first();

    if (isset($post)) return true;

    return false;
  }
}