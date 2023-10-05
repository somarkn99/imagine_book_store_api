<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SearchBookController;
use Illuminate\Support\Facades\Route;

//  API Routes

/*
|--------------------------------------------------------------------------
| Books Routes
|--------------------------------------------------------------------------
|
*/

Route::apiResource('book', BookController::class);
Route::post('book/search', SearchBookController::class);

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
|
*/
Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout')->middleware('auth:api');
    Route::post('refresh', 'refresh')->middleware('auth:api');
});

/*
|--------------------------------------------------------------------------
| Cart Routes
|--------------------------------------------------------------------------
|
*/
Route::apiResource('cart', CartController::class)->middleware('auth:api');

/*
|--------------------------------------------------------------------------
| Order Routes
|--------------------------------------------------------------------------
|
*/
Route::apiResource('order', OrderController::class)->middleware('auth:api');

Route::fallback(function () {
    return response()->json([
        'message' => 'You have probably written something wrong !',
    ], 404);
});
