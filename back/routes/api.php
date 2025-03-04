<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;

Route::get('movies', [MovieController::class, 'index']); // Obtener todas las películas
Route::get('movies/{id}', [MovieController::class, 'show']); // Obtener una película por ID



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
