<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\UserService;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    private function getUser(Request $request)
    {
        $cookieToken = $request->cookie('custom_token');

        $user = $this->userService->findUserByToken($cookieToken);

        return $user;
    }

    public function getUserById(Request $request)
    {
        $user = $this->getUser($request);
        
        if (!$user) {
            return response()->json(['error' => 'Usuário não encontrado'], 404);
        }

        $userType = $user->getTable();
        $userData = $this->getUserDataByType($user, $userType);
        
        return response()->json(['user' => $userData], 200);

    }

    private function getUserDataByType($user, $userType)
    {
        switch ($userType) {
            case 'pcd_users':
                return $this->getPcdUser($user);
            case 'standar_user':
                return $this->getStandarUser($user);
            case 'admin_user':
                return $this->getAdminUser($user);
            default:
                return null;
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
            'is_institution' => $user->is_institution,
            'email' => $user->email
        ];
    }
}
