<?php

use App\Http\Controllers\Api\V1\Admin\CategoryController;
use App\Http\Controllers\Api\V1\Admin\ProductController;
use App\Http\Controllers\Api\V1\Admin\ProductImportController;
use App\Http\Controllers\Api\V1\Auth\AuthenticationController;
use App\Http\Controllers\Api\V1\Auth\SocialiteAuthController;
use App\Http\Controllers\Api\V1\Customer\OrderController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

# Public routes
Route::group(['prefix' => 'v1/', 'middleware' => 'api'], function () {
    Route::post('sign-up', [AuthenticationController::class, 'signUp']);
    Route::post('login', [AuthenticationController::class, 'login']);

    #Products
    Route::group(['prefix' => 'products'], function () {
        Route::get('/', [\App\Http\Controllers\Api\V1\Customer\ProductController::class, 'index']);
        Route::get('/{id}', [\App\Http\Controllers\Api\V1\Customer\ProductController::class, 'show']);
    });
});

# Social login
Route::group(['prefix' => 'v1/auth/{provider}/', 'middleware' => 'api'], function () {
    Route::get('redirect', [SocialiteAuthController::class, 'redirectToProvider']);
    Route::get('callback', [SocialiteAuthController::class, 'handleProviderCallback']);
});

# Private Routes
Route::group(['prefix' => 'v1/', 'middleware' => ['auth:api']], function () {
    # Logout
    Route::post('logout', [AuthenticationController::class, 'logout']);

    # Admin Routes
    Route::group(['prefix' => 'admin', 'middleware' => AdminMiddleware::class], function () {
        # Category
        Route::group(['prefix' => 'categories'], function () {
            Route::get('/', [CategoryController::class, 'index']);
            Route::get('/{id}', [CategoryController::class, 'show']);
            Route::post('/', [CategoryController::class, 'store']);
            Route::put('/{id}', [CategoryController::class, 'update']);
            Route::delete('/{id}', [CategoryController::class, 'destroy']);
        });

        # Product
        Route::group(['prefix' => 'products'], function () {
            Route::get('/', [ProductController::class, 'index']);
            Route::get('/{id}', [ProductController::class, 'show']);
            Route::post('/', [ProductController::class, 'store']);
            Route::put('/{id}', [ProductController::class, 'update']);
            Route::delete('/{id}', [ProductController::class, 'destroy']);
        });

        Route::post('products/import', [ProductImportController::class, 'import']);
    });

    # Customer Routes
    Route::group(['prefix' => 'orders'], function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::get('/{id}', [OrderController::class, 'show']);
        Route::post('/', [OrderController::class, 'create']);
    });
});
