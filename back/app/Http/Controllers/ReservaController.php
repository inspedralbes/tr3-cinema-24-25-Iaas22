<?php

namespace App\Http\Controllers;

use App\Models\Seat;
use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Mail\ReservaConfirmadaMail;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;



class ReservaController extends Controller
{
   
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
                DB::raw("IF(reservas.status IS NULL, 'disponible', 'reservada') as status")
            )
            ->orderBy('seats.row')
            ->orderBy('seats.seat_num')
            ->get();

        $isDiaDelEspectador = now()->dayOfWeek === 3; // Miércoles

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

   
    public function getReservationsByUser($userId)
    {
        $reservations = Reserva::with(['seat', 'session.movie'])
            ->where('user_id', $userId)
            ->where('status', 'confirmada')
            ->get();
    
        if ($reservations->isEmpty()) {
            return response()->json(['error' => 'No hay reservas para este usuario'], 404);
        }
    
        $formattedReservations = $reservations->map(function ($reservation) {
            return [
                'seat_id' => $reservation->seat->seat_id,
                'row' => $reservation->seat->row,
                'seat_num' => $reservation->seat->seat_num,
                'type' => strtoupper($reservation->seat->row) === 'F' ? 'vip' : 'normal',
                'status' => $reservation->status,
                'session_id' => $reservation->session->session_id ?? null,
                'movie' => $reservation->session->movie->title ?? null,
                'date' => $reservation->session->session_date ?? null,
                'time' => $reservation->session->session_time ?? null
            ];
        });
    
        return response()->json($formattedReservations);
    }
    
public function cancelReservation($seatId)
{
    $userId = auth()->id();

    $reservation = Reserva::where('seat_id', $seatId)
        ->where('user_id', $userId)
        ->first();

    if (!$reservation) {
        return response()->json(['error' => 'Reserva no encontrada'], 404);
    }

    $reservation->delete();

    return response()->json(['success' => '✅ Reserva cancelada con éxito']);
}

public function cancelReservations(Request $request)
{
    $request->validate([
        'seat_ids' => 'required|array|min:1|max:10',
        'seat_ids.*' => 'exists:reservas,seat_id',
        'session_id' => 'required|exists:session,session_id'
    ]);

    $seatIds = $request->seat_ids;
    $sessionId = $request->session_id;
    $userId = auth()->id();


    $reservations = Reserva::whereIn('seat_id', $seatIds)
        ->where('session_id', $sessionId)
        ->where('user_id', $userId)
        ->get();

    if ($reservations->isEmpty()) {
        return response()->json([
            'error' => '⚠️ No hay reservas para cancelar o no pertenecen a este usuario'
        ], 404);
    }

    $reservations->each(function ($reservation) {
        $reservation->delete();
    });

    return response()->json([
        'success' => '✅ Reservas canceladas con éxito',
        'butacas_canceladas' => $seatIds
    ]);
}

   
    public function reserveSeat(Request $request)
    {
        $request->validate([
            'seat_id' => 'required|exists:seats,seat_id',
            'session_id' => 'required|exists:session,session_id'
        ]);

        $seatId = $request->seat_id;
        $sessionId = $request->session_id;
        $userId = auth()->id();

        $existingReservation = Reserva::where('seat_id', $seatId)
            ->where('session_id', $sessionId)
            ->exists();

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
        $request->validate([
            'seat_ids' => 'required|array|max:10',
            'seat_ids.*' => 'exists:seats,seat_id',
            'session_id' => 'required|exists:session,session_id'
        ]);

        $seatIds = $request->seat_ids;
        $sessionId = $request->session_id;
        $userId = auth()->id();

        $existingReservations = Reserva::whereIn('seat_id', $seatIds)
            ->where('session_id', $sessionId)
            ->pluck('seat_id')
            ->toArray();

        if ($existingReservations) {
            return response()->json([
                'error' => '⚠️ Algunas butacas ya están reservadas.',
                'butacas_reservadas' => $existingReservations
            ], 400);
        }

        $reservations = collect($seatIds)->map(function ($seatId) use ($sessionId, $userId) {
            return [
                'seat_id' => $seatId,
                'session_id' => $sessionId,
                'user_id' => $userId,
                'status' => 'reservada',
                'created_at' => now(),
                'updated_at' => now()
            ];
        });

        Reserva::insert($reservations->toArray());

        return response()->json([
            'success' => '✅ Butacas reservadas con éxito',
            'butacas_reservadas' => $seatIds
        ]);
    }

  
    public function getTotalPriceByUser($userId)
    {
        $reservas = Reserva::with('seat')
            ->where('user_id', $userId)
            ->get();

        if ($reservas->isEmpty()) {
            return response()->json(['error' => 'No hay reservas para este usuario'], 404);
        }

        $isDiaDelEspectador = now()->dayOfWeek === 3;

        $total = $reservas->sum(function ($reserva) use ($isDiaDelEspectador) {
            $precioNormal = $reserva->seat->type === 'vip' ? 8 : 6;
            $precioConDescuento = $isDiaDelEspectador 
                ? ($reserva->seat->type === 'vip' ? 6 : 4)
                : $precioNormal;

            return $precioConDescuento;
        });

        return response()->json([
            'total' => $total
        ]);
    }

    
    public function confirmReservation(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'apellidos' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'seat_ids' => 'required|array|min:1',
        'seat_ids.*' => 'exists:seats,seat_id',
        'session_id' => 'required|integer',
    ]);

    $userId = auth()->id();
    $seats = Seat::whereIn('seat_id', $request->seat_ids)->get();

    $session = DB::table('session')
                ->join('movies', 'session.movie_id', '=', 'movies.movie_id')
                ->where('session.session_id', $request->session_id)
                ->first(['movies.title', 'session.session_date', 'session.session_time']);

    $total = 0;
    $seatsWithPrices = [];

    foreach ($seats as $seat) {
        $precio = $seat->type === 'vip' ? 8 : 6;
        $total += $precio;

        $seatsWithPrices[] = [
            'seat_id' => $seat->seat_id,
            'row' => $seat->row,
            'seat_num' => $seat->seat_num,
            'type' => $seat->type,
            'precio' => $precio
        ];
    }

    foreach ($seatsWithPrices as $seatWithPrice) {
        Reserva::where('seat_id', $seatWithPrice['seat_id'])
            ->where('user_id', $userId)
            ->where('status', 'reservada')
            ->where('session_id', $request->session_id)
            ->update([
                'status' => 'confirmada',
                'name' => $request->name,
                'apellidos' => $request->apellidos,
                'email' => $request->email,
                'precio' => $seatWithPrice['precio'],
                'session_id' => $request->session_id,
                'compra_dia' => now()->toDateString(),
                'compra_hora' => now()->toTimeString(),
                'updated_at' => now()
            ]);
    }

    $pdf = PDF::loadView('reserva.pdfform', [
        'name' => $request->name,
        'apellidos' => $request->apellidos,
        'email' => $request->email,
        'seats' => $seatsWithPrices,
        'total' => $total,
        'movie_title' => $session->title ?? 'Película no disponible',
        'session_date' => $session->session_date ?? 'Fecha no disponible',
        'session_time' => $session->session_time ?? 'Hora no disponible'
    ]);

    $filename = 'reserva_'.time().'.pdf';
    $pdfPath = 'pdfs/'.$filename;
    
    Storage::disk('public')->put($pdfPath, $pdf->output());

    Mail::to($request->email)->send(new ReservaConfirmadaMail(
        $request->name,
        $request->apellidos,
        $seatsWithPrices,
        $total,
        $request->session_id,
        $session->title ?? 'Película no disponible',
        $session->session_date ?? 'Fecha no disponible',
        $session->session_time ?? 'Hora no disponible',
        storage_path('app/public/'.$pdfPath) 
    ));

    return response()->json([
        'success' => '✅ Reserva confirmada con éxito',
        'total' => $total,
        'session_id' => $request->session_id,
        'seats' => $seatsWithPrices,
        'pdf_path' => $pdfPath 
    ]);
}
public function getConfirmedReservations(Request $request)
{
    if (auth()->user()->role !== 'admin') {
        return response()->json(['error' => 'No tienes permisos para acceder a esta información'], 403);
    }

    try {
        $request->validate([
            'date' => 'required|date_format:Y-m-d'
        ]);

        $date = $request->date;

        $reservations = Reserva::with(['seat', 'session.movie', 'user'])
            ->where('compra_dia', $date)
            ->where('status', 'confirmada')
            ->get();

        $formattedReservations = $reservations->map(function ($reservation) {
            return [
                'reserva_id' => $reservation->reserva_id,
                'user' => [
                    'id' => $reservation->user->id,
                    'name' => $reservation->user->name,
                    'email' => $reservation->user->email,
                ],
                'seat' => [
                    'id' => $reservation->seat->seat_id,
                    'row' => $reservation->seat->row,
                    'number' => $reservation->seat->seat_num,
                    'type' => $reservation->seat->type ?? (strtoupper($reservation->seat->row) === 'F' ? 'vip' : 'normal'),
                ],
                'session' => [
                    'id' => $reservation->session->session_id ?? null,
                    'movie' => $reservation->session->movie->title ?? null,
                    'date' => $reservation->session->session_date ?? null,
                    'time' => $reservation->session->session_time ?? null,
                ],
                'reservation_details' => [
                    'price' => $reservation->precio,
                    'purchase_date' => $reservation->compra_dia,
                    'purchase_time' => $reservation->compra_hora,
                    'name' => $reservation->name,
                    'lastname' => $reservation->apellidos,
                    'email' => $reservation->email,
                    'status' => $reservation->status,
                ]
            ];
        });

        $countByType = [
            'normal' => $reservations->where('seat.type', 'normal')->count(),
            'vip' => $reservations->where('seat.type', 'vip')->count(),
        ];

        return response()->json([
            'date' => $date,
            'total' => $reservations->count(),
            'countByType' => $countByType,  
            'reservations' => $formattedReservations
        ]);
    } catch (\Exception $e) {
        \Log::error("Error al obtener reservas: " . $e->getMessage());
        return response()->json(['error' => 'Hubo un error al procesar la solicitud'], 500);
    }
}

public function getAvailableDates()
{
    if (auth()->user()->role !== 'admin') {
        return response()->json(['error' => 'No tienes permisos para acceder a esta información'], 403);
    }

    try {
        $dates = Reserva::where('status', 'confirmada')
            ->distinct()
            ->pluck('compra_dia');

        return response()->json([
            'dates' => $dates
        ]);

    } catch (\Exception $e) {
        \Log::error("Error al obtener fechas: " . $e->getMessage());
        return response()->json(['error' => 'Hubo un error al procesar la solicitud'], 500);
    }
}

}
