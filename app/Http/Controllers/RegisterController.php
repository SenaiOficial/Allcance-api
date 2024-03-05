<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserStandar;
use App\Models\UserAdmin;
use App\Models\UserPcd;
use App\Models\InstitutionalToken;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\RegisterStandarUser;
use App\Http\Requests\RegisterAdminRequest;
use App\Services\RegisterService;

class RegisterController extends Controller
{
    protected $registerService;

    public function __construct(RegisterService $registerService)
    {
        $this->registerService = $registerService;
    }

    public function userPcd(RegisterRequest $request)
    {
        return $this->registerService->registerUser($request, UserPcd::class);
    }
    public function userStandar(RegisterStandarUser $request)
    {
        return $this->registerService->registerUser($request, UserStandar::class);
    }
    public function userAdmin(RegisterAdminRequest $request)
    {
        return $this->registerService->registerUser($request, UserAdmin::class);
    }
}
