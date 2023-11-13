<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Address;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\PcdController;
use App\Http\Controllers\RegisterController;

Route::get('/', function () {
    return "It's alive!";
});

Route::get('/users', function () {
    return User::all();
});

Route::get('/addresses', function () {
    return Address::all();
});

Route::get('/users/{userId}/addresses', [AddressController::class, 'showAddresses']);

Route::post('/user', [RegisterController::class, 'userPcd']);

Route::post('/user-standar', [RegisterController::class, 'userStandar']);

Route::post('/user-admin', [RegisterController::class, 'userAdmin']);

Route::post('/address/{userId}', [AddressController::class, 'store']);

Route::post('/login', [LoginController::class, 'login'])->name('api.login');

Route::post('/user-pcd/{userId}', [PcdController::class, 'store']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
