<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\InstitutionalToken;

class TokenController extends Controller
{
    protected $user;
    public function __construct()
    {
        $this->user = auth('admin')->user();
    }

    public function generateToken(Request $request)
    {
        $user = $this->user;
        $token = makeRandomToken();

        try {
            InstitutionalToken::create([
                'user_id' => $user->id,
                'token' => $token,
                'expires_at' => Carbon::now()->addMinutes(10)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'O token é válido por 10 minutos',
                'token' => $token
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error ao gerar token.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
