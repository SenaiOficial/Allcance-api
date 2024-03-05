<?php

use App\Http\Controllers\ResetPasswordController;
use App\Services\RegisterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\PcdController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\TokenController;
use App\Http\Controllers\SuggestionsController;
use App\Http\Controllers\CookieController;
use App\Http\Controllers\ForgetPasswordController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashBoardController;

Route::get('/', function () {
    return "It's alive!";
});

Route::middleware(['auth'])->group(function () {
    Route::get('/users/{userId}/addresses', [AddressController::class, 'showAddresses']);
    Route::get('/get-user', [UserController::class, 'getUserById']);
    Route::get('/logout', [CookieController::class, 'clearAccessToken']);

    Route::prefix('address')->group(function() {
        Route::put('/update-address', [AddressController::class, 'update']);
    });

    Route::prefix('suggestions')->group(function () {
        Route::get('/approved', [SuggestionsController::class, 'showApproved']);
        Route::post('/', [SuggestionsController::class, 'store']);
    });

    Route::prefix('password')->group(function () {
        Route::put('/reset-password', [ResetPasswordController::class, 'resetPassword']);
    });

    Route::middleware(['admin'])->group(function () {
        Route::get('/generate-token', [TokenController::class, 'generateToken']);

        Route::prefix('suggestions')->group(function () {
            Route::get('/', [SuggestionsController::class, 'showSuggestionsReq']);
            Route::put('/approve/{id}', [SuggestionsController::class, 'update']);
            Route::delete('/delete/{id}', [SuggestionsController::class, 'delete']);
        });

        Route::prefix('dashboards')->group(function () {
            Route::get('/generate-dashboard-pcds', [DashBoardController::class, 'getPcdsReport']);
        });
    });
});

Route::prefix('password')->group(function () {
    Route::post('/reset-password', [ForgetPasswordController::class, 'getResetToken']);
    Route::post('/validate-token', [ForgetPasswordController::class, 'validateToken']);
    Route::post('/update-password', [ForgetPasswordController::class, 'resetForgotenPassword']);
});

Route::post('/check-user-register', [RegisterController::class, 'checkExistUser']);

Route::post('/user-pcd', [RegisterController::class, 'userPcd']);

Route::post('/user-standar', [RegisterController::class, 'userStandar']);

Route::post('/user-admin', [RegisterController::class, 'userAdmin']);

Route::post('/address/{userId}', [AddressController::class, 'store']);

Route::post('/login', [LoginController::class, 'login'])->name('api.login');

Route::post('/user-pcd/{userId}', [PcdController::class, 'store']);

