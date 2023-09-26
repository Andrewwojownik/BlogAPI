<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/registration', [\App\Http\Controllers\RegistrationController::class, 'store'])->middleware('guest');
Route::get('/posts/{page?}', [\App\Http\Controllers\PublicPostController::class, 'index'])->where('page', '[0-9]+');

Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);
Route::post('/refresh', [\App\Http\Controllers\AuthController::class, 'refresh']);
Route::get('/me', [\App\Http\Controllers\AuthController::class, 'me']);
