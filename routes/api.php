<?php

use App\Http\Controllers\API\V1\Admin\AdminController;
use App\Http\Controllers\API\V1\Admin\Auth\AuthenticateController;
use Illuminate\Support\Facades\Route;

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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});


Route::group(['prefix' => '/v1'], function () {

    Route::group(['prefix' => '/admin'], function () {
        Route::post('/create', [AdminController::class, 'store']);
        Route::post('/login', [AuthenticateController::class, 'login']);
        Route::post('/logout', [AuthenticateController::class, 'logout'])->middleware('auth:api');


    });
});
