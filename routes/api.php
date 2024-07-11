<?php

use App\Http\Controllers\ConfigurationController;
use App\Http\Controllers\InstitutionController;
use App\Services\RegisterService;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeficiencyController;
use App\Http\Controllers\FeedsController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\TokenController;
use App\Http\Controllers\SuggestionsController;
use App\Http\Controllers\ForgetPasswordController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashBoardController;

Route::get('/', function () {
    return "It's alive!";
});

Route::get('/docker-health-check', function () {
    return response('ok', 200);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/get-user', [UserController::class, 'me']);
    Route::get('/get-deficiencies', [DeficiencyController::class, 'getDeficiencies']);
    Route::get('/logout', [LoginController::class, 'logout']);

    Route::prefix('institution')->group(function () {
        Route::get('/get/{param}', [InstitutionController::class, 'getInstitutions']);
    });

    Route::prefix('dashboards')->group(function () {
        Route::get('/generate/{location?}', [DashBoardController::class, 'getPcdsReport']);
        Route::get('/locations', [DashBoardController::class, 'getLocations']);
    });

    Route::prefix('feeds')->group(function () {
        Route::get('/posts', [FeedsController::class, 'get']);
        Route::get('/ranking', [FeedsController::class, 'getRanking']);
        Route::get('/my-posts/{institution}', [FeedsController::class, 'getPostByInstitution']);
    });

    Route::prefix('address')->group(function () {
        Route::put('/update-address', [AddressController::class, 'update']);
    });

    Route::prefix('suggestions')->group(function () {
        Route::get('/approved', [SuggestionsController::class, 'showApproved']);
        Route::post('/', [SuggestionsController::class, 'store']);
    });

    Route::prefix('password')->group(function () {
        Route::put('/reset-password', [ResetPasswordController::class, 'resetPassword']);
    });

    Route::prefix('configuration')->group(function () {
        Route::get('/get-config-options', [ConfigurationController::class, 'getOptions']);
        Route::get('/get-user', [ConfigurationController::class, 'getConfig']);
        Route::post('/create', [ConfigurationController::class, 'createConfig']);
    });

    Route::middleware(['institution'])->group(function () {
        Route::prefix('feeds')->group(function () {
            Route::post('/create-new-post', [FeedsController::class, 'store']);
            Route::put('/update-post/{id}', [FeedsController::class, 'update']);
            Route::delete('/delete-post/{id}', [FeedsController::class, 'delete']);
        });
    });

    Route::middleware(['admin'])->group(function () {
        Route::get('/generate-token', [TokenController::class, 'generateToken']);

        Route::prefix('institution')->group(function () {
            Route::get('/get-all', [InstitutionController::class, 'get']);
            Route::post('/block-user', [InstitutionController::class, 'blockInstitution']);
        });

        Route::prefix('deficiency')->group(function () {
            Route::post('/create', [DeficiencyController::class, 'store']);
            Route::put('/update/{id}', [DeficiencyController::class, 'update']);
            Route::post('/delete', [DeficiencyController::class, 'delete']);
        });

        Route::prefix('suggestions')->group(function () {
            Route::get('/', [SuggestionsController::class, 'showSuggestionsReq']);
            Route::put('/approve/{id}', [SuggestionsController::class, 'update']);
            Route::delete('/delete/{id}', [SuggestionsController::class, 'delete']);
        });
    });

    Route::prefix('cache')->middleware('admin')->group(function () {
       Route::get('/feed', [FeedsController::class, 'cleanCache']);
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

Route::get('/profile', [RegisterService::class, 'profile']);

Route::post('/check-user-register', [RegisterController::class, 'checkExistUser']);

Route::post('/user-pcd', [RegisterController::class, 'userPcd']);

Route::post('/user-standar', [RegisterController::class, 'userStandar']);

Route::post('/user-admin', [RegisterController::class, 'userAdmin']);

Route::post('/login', [LoginController::class, 'login']);
