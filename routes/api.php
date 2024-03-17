<?php

use App\Http\Controllers\DeficiencyController;
use App\Http\Controllers\ResetPasswordController;
use App\Services\FeedsService;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AddressController;
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
    Route::get('/get-user', [UserController::class, 'getUserById']);
    Route::get('/logout', [CookieController::class, 'clearAccessToken']);

    Route::prefix('feeds')->group(function() {
        Route::get('/posts', [FeedsService::class, 'get']);
    });

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

        Route::prefix('deficiency')->group(function () {
            Route::post('/create-new-deficiency', [DeficiencyController::class, 'store']);
            Route::delete('/delete-deficiency/{id}', [DeficiencyController::class, 'delete']);
        });

        Route::prefix('feeds')->group(function() {
            Route::post('/create-new-post', [FeedsService::class, 'store']);
        });
    });
});

Route::prefix('password')->group(function () {
    Route::post('/reset-password', [ForgetPasswordController::class, 'getResetToken']);
    Route::post('/validate-token', [ForgetPasswordController::class, 'validateToken']);
    Route::post('/update-password', [ForgetPasswordController::class, 'resetForgotenPassword']);
});

Route::prefix('deficiency')->group(function () {
    Route::get('get', [DeficiencyController::class, 'get']);
    Route::get('get-types', [DeficiencyController::class, 'getTypes']);
});

Route::post('/check-user-register', [RegisterController::class, 'checkExistUser']);

Route::post('/user-pcd', [RegisterController::class, 'userPcd']);

Route::post('/user-standar', [RegisterController::class, 'userStandar']);

Route::post('/user-admin', [RegisterController::class, 'userAdmin']);

Route::post('/login', [LoginController::class, 'login'])->name('api.login');
