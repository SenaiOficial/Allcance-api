<?php

namespace App\Http\Controllers;

use App\Jobs\InstitutionTokenJob;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\InstitutionalToken;

class TokenController extends Controller
{
    public function generateToken(Request $request)
    {
        $token = makeRandomToken();

        InstitutionalToken::create(['institutional_token' => $token]);

        // InstitutionTokenJob::dispatch($token)->delay(now()->addMinutes(5));

        return response()->json([
            'message' => 'O token é válido por 5 minutos',
            'token' => $token
        ], 200);
    }
}
