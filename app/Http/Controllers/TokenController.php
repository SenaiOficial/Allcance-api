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
}
