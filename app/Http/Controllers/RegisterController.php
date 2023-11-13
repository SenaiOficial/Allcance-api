<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserStandar;
use App\Models\UserAdmin;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\RegisterStandarUser;
use App\Http\Requests\RegisterAdminRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    public function index()
    {
        try{
            $users = User::all();
            return response()->json($users);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao criar usuário'], 500);
        }
    }
    public function store(Request $request, $model)
    {
        try {
            $validatedData = $request->validated();

            $validatedData['password'] = Hash::make($validatedData['password']);

            $user = $model::create($validatedData);

            if(!$user) {
                Log::error('Erro ao criar usuário');
                return response()->json(['error' => 'Erro ao criar usuário']);
            }

            Log::info('Usuário criado com sucesso');
            return response()->json(['message' => 'Usuário criado com sucesso', 'user_id' => $user->id]);
        } catch (\Exception $e) {
            Log::error('Erro ao criar usuário :' . $e->getMessage());
            return response()->json(['errors' => $e->getMessage()]);
        }
    }

    public function userPcd(RegisterRequest $request)
    {
        return $this->store($request, User::class);
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
