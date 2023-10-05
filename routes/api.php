<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\SearchBookController;
use Illuminate\Http\Request;
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
