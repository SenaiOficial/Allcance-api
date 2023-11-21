<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class CookieController extends Controller
{
    public function setAcessToken($acessToken)
    {
        $cookie = Cookie::make('custom_token', $acessToken, 480);
        
        return response()->json(['message' => 'Sessão iniciada', 'acess_token' => $acessToken])->withCookie($cookie);
    }

    public function clearAcessToken()
    {
        $cookie = Cookie::forget('custom_token');

        return response()->json(['message' => 'Cookie removido!'])->withCookie($cookie);
    }
}
