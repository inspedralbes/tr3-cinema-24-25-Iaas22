<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReservationController;

Route::get('/seats/{movie_id}', [ReservationController::class, 'showSeats']);
Route::post('/reserve', [ReservationController::class, 'createReservation']);



Route::get('/movies', [MovieController::class, 'index']);  // Obtener todas las películas
Route::get('/movies/{id}', [MovieController::class, 'show']); // Obtener película por ID
Route::post('/movies', [MovieController::class, 'store']);  // Crear una nueva película
Route::put('/movies/{id}', [MovieController::class, 'update']); // Actualizar una película
Route::delete('/movies/{id}', [MovieController::class, 'destroy']); // Eliminar una película


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Rutas públicas (registro y login)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

//Requiere autentificación.
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);





