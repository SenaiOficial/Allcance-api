<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class CookieController extends Controller
{
    public function setAccessToken($accessToken)
    {
        $this->clearAccessToken();

        $cookie = Cookie::make('custom_token', $accessToken, 480, '/');
        
        return response()->json([
        'message' => 'Sessão iniciada',
        'access_token' => $accessToken])->withCookie($cookie);
    }

    public function clearAccessToken()
    {
        $cookie = Cookie::forget('custom_token');

        return response()->json(['message' => 'Cookie removido!'])->withCookie($cookie);
    }
}
