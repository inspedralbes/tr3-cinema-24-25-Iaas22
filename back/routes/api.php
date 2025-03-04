<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use Illuminate\Support\Facades\Http;

Route::get('/movies', [MovieController::class, 'index']); // Buscar películas
Route::get('/movies/{imdbID}', [MovieController::class, 'show']); // Obtener detalles de una película

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
