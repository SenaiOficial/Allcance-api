<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Suggestions;
use Illuminate\Support\Facades\Auth;

class SuggestionsController extends Controller
{

    public function index()
    {
        $suggestions = Suggestions::all();
        return view("suggestions.index", compact("suggestions"));
    }

    public function store(Request $request)
    {
        $user = Auth::guard('web')->user();

        $request->validate([
            'content' => 'required, string, max:1000'
        ]);

        dd($user);

        $suggestion = new Suggestions([
            'user_id' => $user,
            'content' => 'teste',
        ]);

        $suggestion->save();

        return response()->json(['message' => 'Sua sugestão foi enviada com sucesso!']);
    }
}
