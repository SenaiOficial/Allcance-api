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
            return response()->json(['user' => $user->getAttributes()]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao obter usuário']);
        }
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
