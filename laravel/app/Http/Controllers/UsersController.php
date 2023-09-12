<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Users;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;

class UserController extends Controller
{
    public function index()
    {
        $user = Users::all();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(RegisterRequest $request)
    {
        $validatedData = $request->validated();
        $user = Users::create($validatedData);

        return redirect()->route('users.index')->with('success', 'Usuário criado com sucesso!');
    }

    public function edit(Users $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(RegisterRequest $request, Users $user)
    {
        $validatedData = $request->validated();
        $user->update($validatedData);

        return redirect()->route('users.index')->with('success', 'Usuário atualizado com sucesso!');
    }

    public function destroy(Users $user)
    {
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Usuário excluído com sucesso!');
    }
}
