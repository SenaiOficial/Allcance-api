<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
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

    public function store(RegisterRequest $request)
    {
        try {
            $validatedData = $request->validated();
            
            $validatedData['password'] = Hash::make($validatedData['password']);
            
            $user = User::create($validatedData);

            if (!$user) {
                Log::error('Erro ao criar usuário: Falha na criação do usuário');
                return response()->json(['error' => 'Erro ao criar usuário1'], 500);
            }

            Log::info('Usuário criado com sucesso');
            return response()->json(['message' => 'Usuário criado com sucesso', 'user_id' => $user->id]);
        } catch (\Exception $e) {
            Log::error('Erro ao criar usuário: ' . $e->getMessage());
            return response()->json(['errors' => $e->getMessage()], 500);
        }    
    }

    protected function getErrors($validator)
    {
        $errors = [];

        if ($validator->errors()->has('date_of_birth')) {
            $errors['date_of_birth'] = $validator->errors()->first('date_of_birth');
        }
    
        if ($validator->errors()->has('email')) {
            $errors['email'] = $validator->errors()->first('email');
        }
    
        if ($validator->errors()->has('cpf')) {
            $errors['cpf'] = $validator->errors()->first('cpf');
        }

        return $errors;
    }

    public function update(RegisterRequest $request, User $user)
    {
        $validatedData = $request->validated();

        $user->update($validatedData);

        return response()->json(['message' => 'Usuário atualizado com sucesso']);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return response()->json(['message' => 'Usuário excluído com sucesso']);
    }

    public function showAddresses($userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        }

        $addresses = $user->addresses;

        return response()->json(['addresses' => $addresses]);
    }
}