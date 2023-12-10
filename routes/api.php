<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Address;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\PcdController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\TokenController;
use App\Http\Controllers\SuggestionsController;

Route::get('/', function () {
    return "It's alive!";
});

Route::middleware(['ensureUserIsLogged'])->group(function () {
    Route::get('/users/{userId}/addresses', [AddressController::class, 'showAddresses']);

    Route::get('/generate-token', [TokenController::class, 'generateToken']);

    Route::get('/get-user/{userType}/{id}', [UserController::class, 'getUserById']);

    Route::get('/suggestions/{userId}', [SuggestionsController::class, 'show']);

    Route::post('/suggestions', [SuggestionsController::class, 'store']);
});

Route::post('/user-pcd', [RegisterController::class, 'userPcd']);

Route::post('/user-standar', [RegisterController::class, 'userStandar']);

Route::post('/user-admin', [RegisterController::class, 'userAdmin']);

Route::post('/address/{userId}', [AddressController::class, 'store']);

Route::post('/login', [LoginController::class, 'login'])->name('api.login');

Route::post('/user-pcd/{userId}', [PcdController::class, 'store']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
