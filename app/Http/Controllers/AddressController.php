<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Routing\Controller;
use App\Http\Requests\AddressRequest;
use App\Models\UserPcd;
use Illuminate\Http\Request;
use App\Services\UserService;
use Log;

class AddressController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    private function getUser(Request $request)
    {
        $bearer = $request->bearerToken();

        $user = $this->userService->findUserByToken($bearer);

        return $user;
    }

    public function update(Request $request)
    {
        $user = $this->getUser($request);

        $this->validateUser($user);

        try {
            $userId = $user->id;
            $requestData = $request->only(['neighborhood', 'street', 'street_number', 'street_complement']);

            $userPcd = UserPcd::find($userId);

            $userPcd->update($requestData);

            return response()->json(['message' => 'Campos atualizados com sucesso'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function validateUser($user)
    {
        if ($user->getTable() !== 'pcd_users') {
            abort(401, 'Unauthorized');
        }
    }
}
