<?php

namespace App\Http\Controllers;

use App\Models\Session;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function index()
    {
        $sessions = Session::with('movie')->get();

        return response()->json($sessions, Response::HTTP_OK);
    }

    public function show($id)
    {
        $session = Session::with('movie')->find($id);

        if (!$session) {
            return response()->json(['message' => 'Session not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($session, Response::HTTP_OK);
    }
  
    public function getSessionsByMovie($movieId)
    {
        $sessions = Session::with('movie')->where('movie_id', $movieId)->get();
    
        if ($sessions->isEmpty()) {
            return response()->json(['message' => 'No hay sesiones disponibles para esta película.'], Response::HTTP_NOT_FOUND);
        }
    
        return response()->json($sessions, Response::HTTP_OK);
    }
  

    public function getSessionDateByMovieId($movieId)
{
    $session = Session::where('movie_id', $movieId)->select('session_date')->first();

    if (!$session) {
        return response()->json(['message' => 'No hay sesión disponible para esta película.'], Response::HTTP_NOT_FOUND);
    }

    $formattedDate = date('Y-m-d', strtotime($session->session_date));

    return response()->json(['session_date' => $formattedDate], Response::HTTP_OK);
}

public function store(Request $request)
{
    $validated = $request->validate([
        'movie_id' => 'required|exists:movies,movie_id',
        'session_date' => 'required|date',
        'session_time' => 'required|date_format:H:i',
        'special_day' => 'required|in:0,1', 
    ]);

    $session = Session::create([
        'movie_id' => $validated['movie_id'],
        'session_date' => $validated['session_date'],
        'session_time' => $validated['session_time'],
        'special_day' => $validated['special_day'], 
    ]);

    return response()->json($session, Response::HTTP_CREATED);
}

public function destroy($id)
{
    $session = Session::find($id);

    if (!$session) {
        return response()->json(['message' => 'Session not found'], Response::HTTP_NOT_FOUND);
    }


    $session->delete();

    return response()->json([
        'message' => 'Session deleted successfully',
        'session' => $session 
    ], Response::HTTP_OK);
}
public function getAllSessions()
{
    $sessions = DB::table('session')
        ->join('movies', 'session.movie_id', '=', 'movies.movie_id')
        ->select('session.session_id', 'session.session_date', 'session.session_time', 'movies.title')
        ->get();

    $sessions->transform(function ($session) {
        $session->is_dia_del_espectador = (new \Carbon\Carbon($session->session_date))->dayOfWeek === 3; // 3 es miércoles
        return $session;
    });

    return response()->json($sessions);
}


}
