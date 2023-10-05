<?php

use App\Http\Controllers\BookController;
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
