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
    $postValidate = $this->validateTimePost($type, $user->id);

    try {
      if ($postValidate) {
        return response()->json(['message' => 'Você já fez uma postagem hoje, tente novamente outro dia.'], 400);
      }

      $content = $request->validate(['content' => 'required|string|max:1000']);

      Suggestions::create([
        'content' => $content['content'],
        'user_id' => $user->id,
        'type' => $type,
        'user' => $user->first_name ?? $user->institution_name
      ]);

      return response()->json(['message' => 'Sua sugestão foi enviada com sucesso!'], 200);
    } catch (\Exception $e) {
      return response()->json($e->getMessage(), 400);
    }
  }

  public function update(Request $request, $id)
  {
    $user = $this->user;

    if ($user->is_admin) {
      $suggestion = Suggestions::find($id);

      $suggestion->update(['approved' => true]);
      $suggestion->save();

      return response()->json(['message' => 'Sugestão atualizada com sucesso!']);
    } else {
      return response()->json(['error' => 'Usuário não autorizado!'], 401);
    }
  }

  public function delete(Request $request, $id)
  {
    $user = $this->user;

    if ($user->is_admin) {
      $suggestion = Suggestions::find($id);

      $suggestion->delete();

      return response()->json(['message' => 'Sugestão excluída com sucesso!'], 200);
    } else {
      return response()->json(['error' => 'Usuário não autorizado!'], 401);
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

  private function showSuggestions($approved)
  {
    try {
      $suggestions = Suggestions::where('approved', $approved)->get();

      foreach ($suggestions as $suggestion) {
        $formattedSuggestions[] = [
          'id' => $suggestion->id,
          'user' => $suggestion->user,
          'content' => $suggestion->content,
          'published_at' => Carbon::parse($suggestion->created_at)->format('d/m')
        ];
      }

      if (!$suggestions) {
        return response()->json([
          'success' => false,
          'message' => 'Nenhuma sugestão encontrada'
        ], 404);
      }

      return response()->json(['suggestions' => $formattedSuggestions]);
    } catch (\Exception $e) {
      return response()->json($e->getMessage(), 400);
    }
  }

  private function validateTimePost($type, $user_id): bool
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