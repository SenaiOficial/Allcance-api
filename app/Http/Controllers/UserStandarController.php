<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterStandarUser;
use App\Models\UserStandar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;


class UserStandarController extends Controller
{
    public function store(RegisterStandarUser $request)
    {
        try {
            $validatedData = $request->validated();

            $validatedData['password'] = Hash::make($validatedData['password']);

            $userStandar = UserStandar::create($validatedData);

            if(!$userStandar) {
                Log::error('Erro ao criar usuário');
                return response()->json(['error' => 'Erro ao criar usuário']);
            }

            Log::info('Usuário criado com sucesso');
            return response()->json(['message' => 'Usuário criado com sucesso', 'user_id' => $userStandar->id]);
        } catch (\Exception $e) {
            Log::error('Erro ao criar usuário :' . $e->getMessage());
            return response()->json(['errors' => $e->getMessage()]);
        }
    }
}
