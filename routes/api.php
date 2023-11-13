<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Address;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\PcdController;
use App\Http\Controllers\UserStandarController;
use App\Http\Controllers\UserAdminController;

Route::get('/', function () {
    return "It's alive!";
});

Route::get('/users', function () {
    return User::all();
});

Route::get('/addresses', function () {
    return Address::all();
});

Route::get('/users/{userId}/addresses', [UserController::class, 'showAddresses']);

Route::post('/user', [UserController::class, 'store']);

Route::post('/user-standar', [UserStandarController::class, 'store']);

Route::post('/user-admin', [UserAdminController::class, 'store']);

Route::post('/address/{userId}', [AddressController::class, 'store']);

Route::post('/login', [LoginController::class, 'login'])->name('api.login');

Route::post('/user-pcd/{userId}', [PcdController::class, 'store']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
