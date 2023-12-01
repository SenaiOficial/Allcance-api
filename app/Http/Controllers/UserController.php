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

            if ($tableName == 'standar_user') {
                $userResponse = $this->getStandarUser($user);
            }

            if ($tableName == 'admin_user') {
                $userResponse = $this->getAdminUser($user);
            }

            return response()->json([ $tableName => $userResponse], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    private function getPcdUser($user)
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

    private function getStandarUser($user)
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
            'state' => $user->state,
            'city' => $user->city
        ];
    }

    private function getAdminUser($user)
    {
        return [
            'institution_name' => $user->institution_name,
            'telephone' => $user->telephone,
            'cnpj' => $user->cnpj,
            'email' => $user->email
        ];
    }

    private function getModelByTableName($tableName)
    {
        $models = [
            'pcd_users' => UserPcd::class,
            'standar_user' => UserStandar::class,
            'admin_user' => UserAdmin::class,
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
