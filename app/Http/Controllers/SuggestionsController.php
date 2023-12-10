<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Suggestions;
use App\Http\Requests\SuggestionsRequest;

class SuggestionsController extends Controller
{
    public function store(SuggestionsRequest $request)
    {
        try {
            $validatedData = $request->validated();
            
            $suggestion = new Suggestions($validatedData);
            
            $suggestion->save();
            
            return response()->json(['message' => 'Sua sugestão foi enviada com sucesso!']);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }
    public function show($userId)
    {
        try {
            $suggestions = Suggestions::with('user')
            ->where('user_id', $userId)
            ->get();

            foreach($suggestions as $suggestion) {
                $formattedSuggestions[] = [
                    'user_name' => $suggestion->user->first_name,
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
}
