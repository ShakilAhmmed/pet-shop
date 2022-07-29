<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\Category\CategoryController;
use App\Http\Controllers\API\V1\User\UserController;
use App\Http\Controllers\API\V1\Admin\AdminController;
use App\Http\Controllers\API\V1\Admin\Auth\AuthenticateController;
use App\Http\Controllers\API\V1\User\Auth\UserAuthenticateController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['prefix' => '/v1'], function () {
    Route::group(['prefix' => '/admin'], function () {
        Route::post('/create', [AdminController::class, 'store']);
        Route::post('/login', [AuthenticateController::class, 'login']);
        Route::middleware('auth:api')->group(function () {
            Route::post('/logout', [AuthenticateController::class, 'logout']);
            Route::get('/user-listing', [AdminController::class, 'userList']);
            Route::put('/user-edit/{uuid}', [AdminController::class, 'userEdit']);
            Route::delete('/user-delete/{uuid}', [AdminController::class, 'userDelete']);
        });
    });
    Route::group(['prefix' => '/user'], function () {
        Route::post('/create', [UserController::class, 'store']);
        Route::post('/login', [UserAuthenticateController::class, 'login']);
        Route::post('/logout', [UserAuthenticateController::class, 'logout'])
            ->middleware('auth:api');
    });
    Route::get('/categories', [CategoryController::class, 'index'])->middleware('auth:api');
    Route::group(['prefix' => '/category', 'middleware' => 'auth:api'], function () {
        Route::post('/create', [CategoryController::class, 'store']);
        Route::get('/{category:uuid}', [CategoryController::class, 'show']);
        Route::put('/{category:uuid}', [CategoryController::class, 'update']);
        Route::delete('/{category:uuid}', [CategoryController::class, 'destroy']);
    });
});
