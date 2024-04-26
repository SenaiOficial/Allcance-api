<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Services\LoginService;

class LoginController extends Controller
{
    protected $loginService;

    public function __construct(LoginService $loginService)
    {
      $this->loginService = $loginService;
    }

    public function login(LoginRequest $request)
    {
        return $this->loginService->login($request);
    }

    public function logout(Request $request)
    {
       return $this->loginService->logout();
    }
}
