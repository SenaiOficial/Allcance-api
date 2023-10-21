<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function show()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->getCredentials();
    
        if (!Auth::validate($credentials)) {
            return response()->json(['error' => 'Email ou senha incorretos'], 401);
        }
    
        $user = Auth::getProvider()->retrieveByCredentials($credentials);
    
        Auth::login($user);
    
        $customToken = Str::random(60);
    
        $user->update(['custom_token' => $customToken]);
    
        return response()->json(['message' => 'Você foi logado com sucesso', 'custom_token' => $customToken], 200);
    }
    
    //     $credentials = $request->getCredentials();

    //     if(!Auth::validate($credentials)):
    //         return redirect()->to('login')
    //             ->withErrors(trans('auth.failed'));
    //     endif;

    //     $user = Auth::getProvider()->retrieveByCredentials($credentials);

    //     Auth::login($user);

    //     return $this->authenticated($request, $user);
    // }    

    protected function authenticated(Request $request, $user) 
    {
        return redirect()->intended();
    }
}
