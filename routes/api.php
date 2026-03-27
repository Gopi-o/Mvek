<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('auth/header')->group(function () {
    Route::post('register', [AuthController::class, 'registerHeader']);
    Route::post('login', [AuthController::class, 'loginHeader']);
    
    Route::middleware('api.token')->group(function () {
        Route::get('profile', [AuthController::class, 'profileHeader']);
    });
});

Route::prefix('auth/jwt')->group(function () {
    Route::post('register', [AuthController::class, 'registerJwt']);
    Route::post('login', [AuthController::class, 'loginJwt']);
    
    Route::middleware('auth:api')->group(function () {
        Route::get('profile', [AuthController::class, 'profileJwt']);
        Route::post('refresh', [AuthController::class, 'refreshJwt']);
        Route::post('logout', [AuthController::class, 'logoutJwt']);
    });
});

Route::get('/test', function () {
    return response()->json([
        'message' => 'API работает!',
        'timestamp' => now()->toDateTimeString()
    ]);
});
