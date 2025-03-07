<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\SeatController;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

// Rutas públicas - Sin autenticación
Route::get('/movies', [MovieController::class, 'index']);  // Obtener todas las películas
Route::get('/movies/{id}', [MovieController::class, 'show']);
Route::post('/movies', [MovieController::class, 'store']);  // Crear una nueva película
Route::put('/movies/{id}', [MovieController::class, 'update']); // Actualizar una película
Route::delete('/movies/{id}', [MovieController::class, 'destroy']); // Eliminar una película

Route::get('/seats', [SeatController::class, 'index']);  // Ver todos los asientos
Route::get('/seats/{id}', [SeatController::class, 'show']);  // Ver un asiento
Route::put('/seats/{id}/reserve', [SeatController::class, 'reserve']);  // Reservar un asiento

// Rutas de autenticación - Registro y Login
Route::post('/register', [AuthController::class, 'register']); 
Route::post('/login', [AuthController::class, 'login']);

// Rutas protegidas por autenticación (requieren token)
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();  // Obtener los datos del usuario autenticado
    });

 
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum'); // Esta ruta debe tener el middleware de autenticación
    
    // Rutas para reservas (requieren autenticación)
    Route::post('/reservations', [ReservationController::class, 'store']);
    Route::get('/reservations', [ReservationController::class, 'index']);
    Route::get('/reservations/{id}', [ReservationController::class, 'show']);
    Route::delete('/reservations/{id}', [ReservationController::class, 'destroy']);
});

