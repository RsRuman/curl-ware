<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'message' => 'Welcome To CurlWare Ecommerce API Test'
    ]);
});
