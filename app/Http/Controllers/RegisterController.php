<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserStandar;
use App\Models\UserAdmin;
use App\Models\UserPcd;
use App\Models\InstitutionalToken;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\RegisterStandarUser;
use App\Http\Requests\RegisterAdminRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\CookieController;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    public function store(Request $request, $model)
    {
        try {
            $validatedData = $request->validated();
            $validatedData['password'] = Hash::make($validatedData['password']);

            if($request->has('pass_code')) {
                $providedToken = $validatedData['pass_code'];
                $storedToken = InstitutionalToken::first()->institutional_token;
                
                if ($providedToken !== $storedToken) {
                    return response()->json(['error' => 'Token inválido'], 400);
                }
            }

            $user = $model::create($validatedData);
            
            if(!$user) {
                Log::error('Erro ao criar usuário');
                return response()->json(['error' => 'Erro ao criar usuário']);
            }

            $accessToken = Str::random(60);
            $user->update(['custom_token' => $accessToken]);

            $cookieController = app(CookieController::class);
            return $cookieController->setAccessToken($accessToken);
        } catch (\Exception $e) {
            if ($e->getCode() == '23000') {
                return response()->json(['error' => 'Email ou CPF já cadastrado'], 400);
            }
            return response()->json(['errors' => $e->getMessage()], 400);
        }
    }

    public function userPcd(RegisterRequest $request)
    {
        return $this->store($request, UserPcd::class);
    }
    public function userStandar(RegisterStandarUser $request)
    {
        return $this->store($request, UserStandar::class);
    }
    public function userAdmin(RegisterAdminRequest $request)
    {
        return $this->store($request, UserAdmin::class);
    }
}
