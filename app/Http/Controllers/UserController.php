<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\RegisterRequest;

class UserController extends Controller
{
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
}
