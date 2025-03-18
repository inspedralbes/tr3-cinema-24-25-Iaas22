<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\SessionController;

// ✅ Rutas públicas - Sin autenticación
Route::get('/movies', [MovieController::class, 'index']);  // Obtener todas las películas
Route::get('/movies/{id}', [MovieController::class, 'show']); // Obtener detalles de una película

// ✅ Rutas para sesiones y reservas sin autenticación
Route::get('/seats', [ReservaController::class, 'index']);
Route::get('/seats/session/{sessionId}', [ReservaController::class, 'getSeatsBySession']);
Route::get('/seat/price/{id}', [ReservaController::class, 'getSeatPriceById']);
Route::get('/compras/total/{userId}', [ReservaController::class, 'getTotalPriceByUser']);
Route::get('/sessions', [SessionController::class, 'index']);
Route::get('/sessions/{id}', [SessionController::class, 'show']);
Route::get('/sessions/movie/{movieId}', [SessionController::class, 'getSessionsByMovie']);
Route::get('/seatsStatus', [ReservaController::class, 'getSeatsWithStatus']);
Route::get('/sessions/date/{movieId}', [SessionController::class, 'getSessionDateByMovieId']);

// ✅ Rutas de autenticación (registro y login)
Route::post('/register', [AuthController::class, 'register']); 
Route::post('/login', [AuthController::class, 'login']); 

// ✅ Rutas protegidas por token con Sanctum
Route::middleware('auth:sanctum')->group(function () {

    // ✅ Obtener datos del usuario autenticado
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // ✅ Endpoint para comprobar si el token es válido
    Route::get('/auth/check', function (Request $request) {
        return response()->json(['status' => 'authenticated']);
    });

    // ✅ Cierre de sesión
    Route::post('/logout', [AuthController::class, 'logout']);

    // ✅ Crear, actualizar y eliminar películas (solo admin)
    Route::post('/movies', [MovieController::class, 'store']);  // Crear una película
    Route::put('/movies/{id}', [MovieController::class, 'update']); // Actualizar una película
    Route::delete('/movies/{id}', [MovieController::class, 'destroy']); // Eliminar una película

    // ✅ Reservar butaca
    Route::post('/reserve-seat', [ReservaController::class, 'reserveSeat']);
    Route::post('/reservar-butacas', [ReservaController::class, 'reserveSeats']);

    // ✅ Rutas para reservas
    Route::post('/reservations', [ReservaController::class, 'store']);
    Route::get('/reservations', [ReservaController::class, 'index']);
    Route::get('/reservations/{id}', [ReservaController::class, 'show']);
    Route::delete('/reservations/{id}', [ReservaController::class, 'destroy']);

    // ✅ Nueva ruta para completar la reserva
    Route::post('/reservar-completa', [ReservaController::class, 'completeReservation']);
    Route::get('/reservations/user/{userId}', [ReservaController::class, 'getReservationsByUser']);

});
