<?php

namespace App\Http\Controllers;

use App\Models\Session;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SessionController extends Controller
{
    // Mostrar todas las sesiones
    public function index()
    {
        $sessions = Session::with('movie')->get();

        return response()->json($sessions, Response::HTTP_OK);
    }

    // Mostrar una sesión por ID
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

    // Formatear la fecha directamente con date()
    $formattedDate = date('Y-m-d', strtotime($session->session_date));

    return response()->json(['session_date' => $formattedDate], Response::HTTP_OK);
}

public function store(Request $request)
{
    // Verificar si el usuario está autenticado
    if (!auth()->check()) {
        return response()->json(['error' => 'No autenticado'], Response::HTTP_UNAUTHORIZED);
    }

    // Verificar si el usuario tiene permisos de administrador
    if (auth()->user()->role !== 'admin') {
        return response()->json(['error' => 'No tienes permisos para crear sesiones'], Response::HTTP_FORBIDDEN);
    }

    try {
        // Validación de los datos
        $validator = Validator::make($request->all(), [
            'movie_id' => 'sometimes|exists:movies,movie_id',
            'session_date' => 'required|date',
            'session_time' => 'required|date_format:H:i:s',
            'special_day' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        // Crear la sesión
        $session = Session::create([
            'movie_id' => $request->movie_id,
            'session_date' => $request->session_date,
            'session_time' => $request->session_time,
            'special_day' => $request->special_day,
        ]);

        return response()->json([
            'message' => 'Sesión creada con éxito',
            'session' => $session
        ], Response::HTTP_CREATED);

    } catch (\Illuminate\Database\QueryException $e) {
        // Si hay un error en la base de datos
        return response()->json([
            'error' => 'Error en la base de datos: ' . $e->getMessage()
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    } catch (\Exception $e) {
        // Si ocurre cualquier otro tipo de error
        return response()->json([
            'error' => 'Error inesperado: ' . $e->getMessage()
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}

     
     // Actualizar una sesión (solo admin)
     public function update(Request $request, $id)
     {
         if (auth()->user()->role !== 'admin') {
             return response()->json(['error' => 'No tienes permisos para actualizar sesiones'], Response::HTTP_FORBIDDEN);
         }
 
         $session = Session::find($id);
 
         if (!$session) {
             return response()->json(['error' => 'Sesión no encontrada'], Response::HTTP_NOT_FOUND);
         }
 
         $validator = Validator::make($request->all(), [
            'movie_id' => 'required|exists:movies,movie_id',
            'session_date' => 'required|date',
            'session_time' => 'required|date_format:H:i:s',
            'special_day' => 'required|boolean',
        ]);
        
         if ($validator->fails()) {
             return response()->json(['errors' => $validator->errors()], Response::HTTP_BAD_REQUEST);
         }
 
         $session->update($request->all());
 
         return response()->json([
             'message' => 'Sesión actualizada con éxito',
             'session' => $session
         ], Response::HTTP_OK);
     }
 
     // Eliminar una sesión (solo admin)
     public function destroy($id)
     {
         if (auth()->user()->role !== 'admin') {
             return response()->json(['error' => 'No tienes permisos para eliminar sesiones'], Response::HTTP_FORBIDDEN);
         }
 
         $session = Session::find($id);
 
         if (!$session) {
             return response()->json(['error' => 'Sesión no encontrada'], Response::HTTP_NOT_FOUND);
         }
 
         $session->delete();
 
         return response()->json(['message' => 'Sesión eliminada con éxito'], Response::HTTP_OK);
     }
 }