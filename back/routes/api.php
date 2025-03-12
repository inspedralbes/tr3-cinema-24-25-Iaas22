<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\SessionController;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

// Rutas públicas - Sin autenticación
Route::get('/movies', [MovieController::class, 'index']);  // Obtener todas las películas
Route::get('/movies/{id}', [MovieController::class, 'show']);
Route::post('/movies', [MovieController::class, 'store']);  // Crear una nueva película
Route::put('/movies/{id}', [MovieController::class, 'update']); // Actualizar una película
Route::delete('/movies/{id}', [MovieController::class, 'destroy']); // Eliminar una película

Route::get('/seats', [ReservaController::class, 'index']);
Route::post('/seats/{seat_id}/reserve', [ReservaController::class, 'reservar']);
Route::get('/seats/session/{sessionId}', [ReservaController::class, 'getSeatsBySession']);
Route::get('/seat/price/{id}', [ReservaController::class, 'getSeatPriceById']);



// ✅ Rutas de sesiones fuera de autenticación (accesibles para todos)
Route::get('/sessions', [SessionController::class, 'index']);
Route::get('/sessions/{id}', [SessionController::class, 'show']); // Mostrar una sesión por ID
Route::get('/sessions/movie/{movieId}', [SessionController::class, 'getSessionsByMovie']);


Route::get('/seatsStatus', [ReservaController::class, 'getSeatsWithStatus']);




// Rutas de autenticación - Registro y Login
Route::post('/register', [AuthController::class, 'register']); 
Route::post('/login', [AuthController::class, 'login']);

// Rutas protegidas por autenticación (requieren token)
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();  // Obtener los datos del usuario autenticado
    });

    Route::post('logout', [AuthController::class, 'logout']); 

    // Rutas para reservas (requieren autenticación)
    Route::post('/reservations', [ReservationController::class, 'store']);
    Route::get('/reservations', [ReservationController::class, 'index']);
    Route::get('/reservations/{id}', [ReservationController::class, 'show']);
    Route::delete('/reservations/{id}', [ReservationController::class, 'destroy']);

});
