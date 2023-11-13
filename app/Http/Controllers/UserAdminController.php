<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterAdminRequest;
use App\Models\UserAdmin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserAdminController extends Controller
{
   public function store(RegisterAdminRequest $request)
    {
        try {
            $validatedData = $request->validated();

            $validatedData['password'] = Hash::make($validatedData['password']);

            $userAdm = UserAdmin::create($validatedData);

            if(!$userAdm) {
                Log::error('Erro ao criar usuário');
                return response()->json(['error' => 'Erro ao criar usuário']);
            }

            Log::info('Administrador criado com sucesso');
            return response()->json(['message' => 'Usuário criado com sucesso', 'user_id' => $userAdm->id]);
        } catch (\Exception $e) {
            Log::error('Erro ao criar usuário :' . $e->getMessage());
            return response()->json(['errors' => $e->getMessage()]);
        }
    }
}
