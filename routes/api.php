<?php

use App\Http\Controllers\Api\V1\Auth\AuthenticationController;
use Illuminate\Support\Facades\Route;

# Public routes
Route::group(['prefix' => 'v1/', 'middleware' => 'api'], function () {
    Route::post('sign-up', [AuthenticationController::class, 'signUp']);
    Route::post('login', [AuthenticationController::class, 'login']);
});

# Private Routes
Route::group(['prefix' => 'v1/', 'middleware' => ['auth:api']], function () {
    // Logout
    Route::post('logout', [AuthenticationController::class, 'logout']);
});
