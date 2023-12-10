<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Suggestions;
use App\Http\Requests\SuggestionsRequest;

class SuggestionsController extends Controller
{

    public function index()
    {
        $suggestions = Suggestions::all();
        return response()->json(['suggestions' => $suggestions]);
    }

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
}
