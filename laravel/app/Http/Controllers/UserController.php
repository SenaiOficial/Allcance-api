<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    public function store(RegisterRequest $request)
    {
        $validatedData = $request->validated();
        $user = User::create($validatedData);

        return response()->json(['message' => 'Usuário criado com sucesso']);
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
}
