<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserStandar;
use App\Models\UserAdmin;
use App\Models\UserPcd;
use App\Http\Requests\RegisterRequest;

class UserController extends Controller
{
    public function getUserById($tableName, $id)
    {
        $model = $this->getModelByTableName($tableName);

        if (!$model) {
            return response()->json(['error' => 'Tabela não encontrada'], 404);
        }

        try {
            $user = $model::find($id);

            if (!$user) {
                return response()->json(['error' => 'Usuário não encontrado'], 404);
            }

            $userResponse = [];
            
            if ($tableName == 'pcd_users') {
                $userResponse = $this->getPcdUser($user);
            }

            return response()->json([ $tableName => $userResponse]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function getPcdUser($user)
    {
        return [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'cellphone' => $user->phone_number,
            'cpf' => $user->cpf,
            'date_of_birth' => $user->date_of_birth,
            'status' => $user->marital_status,
            'gender' => $user->gender,
            'email' => $user->email,
            'cep' => $user->cep,
            'country' => $user->country,
            'state' => $user->state,
            'city' => $user->city,
            'street' => $user->street,
            'street_number' => $user->street_number,
            'street_complement' => $user->street_complement,
            'pcd_type' => $user->pcd_type,
            'pcd' => $user->pcd
        ];
    }

    private function getModelByTableName($tableName)
    {
        $models = [
            'standar_user' => UserStandar::class,
            'admin_user' => UserAdmin::class,
            'pcd_users' => UserPcd::class,
        ];

        return $models[$tableName] ?? null;
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
