<?php

namespace App\Services;

use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use App\Models\Suggestions;

class SuggestionsService
{
  protected $guard;

  public function __construct()
  {
    $this->guard = getActiveGuard();
  }

  public function store(Request $request)
  {
    $user = auth($this->guard)->user();
    $type = $user->getTable();
    $user_id = $user->id;
    $postValidate = $this->validateTimePost($type, $user_id);

    try {
      if ($postValidate) {
        return response()->json(['message' => 'Você já fez uma postagem hoje, tente novamente outro dia.'], 400);
      }

      $content = $request->validate(['content' => 'required|string|max:1000']);

      Suggestions::create([
        'content' => $content['content'],
        'user_id' => $user_id,
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
    $user = auth($this->guard)->user();

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
    $user = auth($this->guard)->user();

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
          'content' => $suggestion->content
        ];
      }

      if ($suggestions->isEmpty()) {
        return response()->json(['message' => 'Nenhuma sugestão encontrada']);
      }

      return response()->json(['suggestions' => $formattedSuggestions]);
    } catch (\Exception $e) {
      return response()->json($e->getMessage(), 400);
    }
  }

  private function validateTimePost($type, $user_id)
  {
    $post = DB::table('suggestions')
      ->where('user_id', '=', $user_id)
      ->where('type', '=', $type)
      ->whereDate('created_at', Carbon::today())
      ->first();

    if (isset($post)) return true;
  }
}