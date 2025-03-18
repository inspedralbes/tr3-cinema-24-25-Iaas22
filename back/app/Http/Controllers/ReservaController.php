<?php

namespace App\Http\Controllers;

use App\Models\Seat;
use App\Models\Reserva;
use Illuminate\Http\Request;

class ReservaController extends Controller
{
    /**
     * Mostrar todas las butacas y su estado por ID de sesión.
     */
    public function getSeatsBySession($sessionId)
    {
        $seats = Seat::leftJoin('reservas', function ($join) use ($sessionId) {
                $join->on('seats.seat_id', '=', 'reservas.seat_id')
                    ->where('reservas.session_id', '=', $sessionId);
            })
            ->select(
                'seats.seat_id',
                'seats.row',
                'seats.seat_num',
                'seats.type',
                \DB::raw("IF(reservas.status IS NULL, 'disponible', 'reservada') as status")
            )
            ->orderBy('seats.row')
            ->orderBy('seats.seat_num')
            ->get();

        $dayOfWeek = date('w');
        $isDiaDelEspectador = ($dayOfWeek == 3);

        $seats->transform(function ($seat) use ($isDiaDelEspectador) {
            $seat->type = strtoupper($seat->row) === 'F' ? 'vip' : 'normal';
            $seat->precio_normal = $seat->type === 'vip' ? 8 : 6;
            $seat->precio_con_descuento = $isDiaDelEspectador 
                ? ($seat->type === 'vip' ? 6 : 4)
                : $seat->precio_normal;

            return $seat;
        });

        return response()->json($seats);
    }

    /**
     * Reservar una butaca.
     */
    public function reserveSeat(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['error' => '⚠️ Token inválido o no proporcionado.'], 401);
        }
    
        $request->validate([
            'seat_id' => 'required|exists:seats,seat_id',
            'session_id' => 'required|exists:session,session_id'
        ]);
    
        $seatId = $request->seat_id;
        $sessionId = $request->session_id;
        $userId = auth()->id();
    
        $existingReservation = Reserva::where('seat_id', $seatId)
                                      ->where('session_id', $sessionId)
                                      ->first();
    
        if ($existingReservation) {
            return response()->json(['error' => 'La butaca ya está reservada'], 400);
        }
    
        Reserva::create([
            'seat_id' => $seatId,
            'session_id' => $sessionId,
            'user_id' => $userId,
            'status' => 'reservada'
        ]);
    
        return response()->json(['success' => '✅ Butaca reservada con éxito']);
    }
    public function reserveSeats(Request $request)
    {
        // ✅ Verificar que el usuario está autenticado
        if (!auth()->check()) {
            return response()->json(['error' => '⚠️ Token inválido o no proporcionado.'], 401);
        }
    
        // ✅ Validar los datos de la solicitud
        $request->validate([
            'seat_ids' => 'required|array|max:10', // ✅ Máximo de 10 butacas a la vez
            'seat_ids.*' => 'exists:seats,seat_id', // ✅ Validar que las butacas existen
            'session_id' => 'required|exists:session,session_id'
        ]);
    
        $seatIds = $request->seat_ids;
        $sessionId = $request->session_id;
        $userId = auth()->id();
    
        // ✅ Verificar si alguna butaca ya está reservada
        $existingReservations = Reserva::whereIn('seat_id', $seatIds)
                                        ->where('session_id', $sessionId)
                                        ->pluck('seat_id')
                                        ->toArray();
    
        if (!empty($existingReservations)) {
            return response()->json([
                'error' => '⚠️ Las siguientes butacas ya están reservadas:',
                'butacas_reservadas' => $existingReservations
            ], 400);
        }
    
        // ✅ Crear las reservas en una sola operación
        $reservations = array_map(function ($seatId) use ($sessionId, $userId) {
            return [
                'seat_id' => $seatId,
                'session_id' => $sessionId,
                'user_id' => $userId,
                'status' => 'reservada',
                'created_at' => now(),
                'updated_at' => now()
            ];
        }, $seatIds);
    
        Reserva::insert($reservations);
    
        return response()->json([
            'success' => '✅ Butacas reservadas con éxito',
            'butacas_reservadas' => $seatIds
        ]);
    }
    
        
    /**
     * Completar reserva y guardar compra
     */
    public function completeReservation(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['error' => '⚠️ Token inválido o no proporcionado.'], 401);
        }

        $request->validate([
            'seat_ids' => 'required|array|max:10',
            'seat_ids.*' => 'exists:seats,seat_id',
            'session_id' => 'required|exists:session,session_id',
            'movie_id' => 'required|exists:movies,movie_id',
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|email|max:255'
        ]);

        $seatIds = $request->seat_ids;
        $sessionId = $request->session_id;
        $movieId = $request->movie_id;
        $userId = auth()->id();
        $name = $request->name;
        $surname = $request->surname;
        $email = $request->email;

        $existingReservations = Reserva::whereIn('seat_id', $seatIds)
                                        ->where('session_id', $sessionId)
                                        ->pluck('seat_id')
                                        ->toArray();

        if (!empty($existingReservations)) {
            return response()->json([
                'error' => '⚠️ Las siguientes butacas ya están reservadas:',
                'butacas_reservadas' => $existingReservations
            ], 400);
        }

        // ✅ Crear las reservas
        $reservations = array_map(function ($seatId) use ($sessionId, $userId) {
            return [
                'seat_id' => $seatId,
                'session_id' => $sessionId,
                'user_id' => $userId,
                'status' => 'reservada',
                'created_at' => now(),
                'updated_at' => now()
            ];
        }, $seatIds);

        Reserva::insert($reservations);

        // ✅ Insertar en la tabla 'compras'
        $reservas = Reserva::whereIn('seat_id', $seatIds)
            ->where('session_id', $sessionId)
            ->get();

        $compras = $reservas->map(function ($reserva) use ($userId, $movieId, $name, $surname, $email) {
            $precio = Seat::where('seat_id', $reserva->seat_id)->value('price');
            return [
                'user_id' => $userId,
                'seat_id' => $reserva->seat_id,
                'movie_id' => $movieId,
                'name' => $name,
                'apellidos' => $surname,
                'email' => $email,
                'precio' => $precio,
                'compra_dia' => now()->format('Y-m-d'),
                'compra_hora' => now()->format('H:i:s'),
                'created_at' => now(),
                'updated_at' => now()
            ];
        });

        \DB::table('compras')->insert($compras->toArray());

        // ✅ Calcular total
        $total = $compras->sum('precio');

        return response()->json([
            'success' => '✅ Butacas reservadas y compra registrada con éxito',
            'butacas_reservadas' => $seatIds,
            'total' => $total
        ]);
    }

    /**
     * Obtener el precio total por usuario
     */
    public function getTotalPriceByUser($userId)
    {
        $compras = \DB::table('reservas')
            ->join('seats', 'reservas.seat_id', '=', 'seats.seat_id')
            ->where('reservas.user_id', $userId)
            ->select('seats.type')
            ->get();

        if ($compras->isEmpty()) {
            return response()->json(['error' => 'No hay butacas reservadas para este usuario'], 404);
        }

        $dayOfWeek = date('w');
        $isDiaDelEspectador = ($dayOfWeek == 3);

        $total = $compras->sum(function ($compra) use ($isDiaDelEspectador) {
            $precioNormal = $compra->type === 'vip' ? 8 : 6;
            $precioConDescuento = $isDiaDelEspectador 
                ? ($compra->type === 'vip' ? 6 : 4)
                : $precioNormal;

            return $precioConDescuento;
        });

        return response()->json([
            'total' => $total
        ]);
    }
    /**
 * Guardar datos del usuario en la base de datos
 */
public function saveUserData(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'surname' => 'required|string|max:255',
        'email' => 'required|email|max:255',
    ]);

    $userId = auth()->id();

    \DB::table('users')->where('id', $userId)->update([
        'name' => $request->name,
        'surname' => $request->surname,
        'email' => $request->email,
        'updated_at' => now(),
    ]);

    return response()->json(['success' => '✅ Datos de usuario guardados con éxito']);
}

/**
 * Mostrar datos de la compra
 */
public function getPurchaseDetails($userId)
{
    $compras = \DB::table('compras')
        ->join('movies', 'compras.movie_id', '=', 'movies.movie_id')
        ->join('users', 'compras.user_id', '=', 'users.id')
        ->where('compras.user_id', $userId)
        ->select(
            'movies.title as movie_name',
            'users.name',
            'users.surname',
            'users.email',
            \DB::raw('COUNT(compras.seat_id) as total_seats'),
            \DB::raw('SUM(compras.precio) as total_price')
        )
        ->groupBy('movies.title', 'users.name', 'users.surname', 'users.email')
        ->get();

    if ($compras->isEmpty()) {
        return response()->json(['error' => '❌ No hay datos de compra para este usuario'], 404);
    }

    return response()->json($compras);
}

}
