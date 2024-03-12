<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Suggestions;
use App\Services\UserService;

class SuggestionsService
{
  protected $userService;

  public function __construct(UserService $userService)
  {
    $this->userService = $userService;
  }

  private function getUser(Request $request)
  {
    $bearer = $request->bearerToken();

    $user = $this->userService->findUserByToken($bearer);

    return $user;
  }

  public function store(Request $request)
  {
    $user = $this->getUser($request);

    try {
      $validatedData = $request->validate([
        'content' => ['required', 'string', 'max:1000']
      ]);

      $validatedData['user'] = $user->first_name;

      $suggestion = new Suggestions($validatedData);
      $suggestion->save();

      return response()->json(['message' => 'Sua sugestão foi enviada com sucesso!']);
    } catch (\Exception $e) {
      return response()->json($e->getMessage(), 400);
    }
  }

  public function update(Request $request, $id)
  {
    $user = $this->getUser($request);

    if ($user->is_institution) {
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
    $user = $this->getUser($request);

    if ($user->is_institution) {
      $suggestion = Suggestions::find($id);

      $suggestion->delete();

      return response()->json(['message' => 'Sugestão excluída com sucesso!']);
    } else {
      return response()->json(['error' => 'Usuário não autorizado!'], 401);
    }
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

  public function showApproved()
  {
    return $this->showSuggestions(true);
  }

  public function showSuggestionsReq()
  {
    return $this->showSuggestions(false);
  }
}