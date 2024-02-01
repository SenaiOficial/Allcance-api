<?php

use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\PcdController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\TokenController;
use App\Http\Controllers\SuggestionsController;

Route::get('/', function () {
    return "It's alive!";
});

Route::middleware(['auth'])->group(function () {
    Route::get('/users/{userId}/addresses', [AddressController::class, 'showAddresses']);
    Route::get('/get-user', [UserController::class, 'getUserById']);

    Route::prefix('suggestions')->group(function () {
        Route::get('/', [SuggestionsController::class, 'showSuggestionsReq']);
    });
    Route::middleware(['admin'])->group(function () {
        Route::get('/generate-token', [TokenController::class, 'generateToken']);

        Route::prefix('suggestions')->middleware(['admin'])->group(function () {
            Route::get('/approved', [SuggestionsController::class, 'showApproved']);
            Route::post('/', [SuggestionsController::class, 'store']);
            Route::put('/approve/{id}', [SuggestionsController::class, 'update']);
            Route::delete('/delete/{id}', [SuggestionsController::class, 'delete']);
        });
    });
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
