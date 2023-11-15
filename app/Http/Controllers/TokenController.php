<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\InstitutionalToken;
use App\Models\UserAdmin;

class TokenController extends Controller
{
    public function generateToken(Request $request)
    {
        $randomToken = Str::random(50);

        $existingToken = InstitutionalToken::first();

        if ($existingToken) {
            $existingToken->update(['institutional_token' => $randomToken]);
        } else {
            InstitutionalToken::create(['institutional_token' => $randomToken]);
        }

        return response()->json(['token:' => $randomToken], 200);
    }

    public function validateToken(Request $request)
    {
        try {
            $providedToken = $request->input('pass_code');
            $storedToken = InstitutionalToken::first()->institutional_token;

            if ($providedToken !== $storedToken) {
                return response()->json(['error' => 'Token inválido'], 400);
            }

            return response()->json(['message' => 'Token válido'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao validar o token'], 500);
        }
    }
}
