<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class UserController extends Controller
{
    protected $guard;

    public function __construct()
    {
        $this->guard = getActiveGuard();
    }
    public function getUserById()
    {
        $user = auth($this->guard)->user();
        
        if (!$user) {
            return response()->json(['error' => 'Usuário não encontrado'], 404);
        }

        return response()->json(['user' => $user], 200);

    }
}
