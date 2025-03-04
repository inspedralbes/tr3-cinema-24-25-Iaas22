<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;

class MovieController extends Controller
{
    // Obtener todas las películas desde la base de datos
    public function index()
    {
        $movies = Movie::all(); // Obtiene todas las películas
        return response()->json($movies, 200);
    }

    // Obtener una película específica por ID desde la base de datos
    public function show($id)
    {
        $movie = Movie::find($id); // Busca la película por ID

        if (!$movie) {
            return response()->json(['error' => 'Película no encontrada'], 404);
        }

        return response()->json($movie, 200);
    }

    public function store(Request $request)
    {
        try {
            // Validar los datos
            $request->validate([
                'title' => 'required|string|max:255',
                'duration' => 'required|integer',
                'genre' => 'required|string|max:255',
                'director' => 'required|string',
                'actors' => 'required|string',
                'sinopsis' => 'required|string',
                'premiere' => 'required|date',
                'room_id' => 'required|exists:rooms,id'
            ]);
    
            // Crear la película en la base de datos
            $movie = Movie::create($request->all());
    
            return response()->json([
                'message' => 'Película creada con éxito',
                'movie' => $movie
            ], 201);
    
        } catch (\Exception $e) {
            // Loggear el error para poder diagnosticarlo
            \Log::error("Error al crear la película: " . $e->getMessage());
    
            // Retornar un mensaje de error
            return response()->json([
                'error' => 'Hubo un error al procesar la solicitud.',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    // Editar una película en la base de datos
public function update(Request $request, $id)
{
    // Buscar la película por ID
    $movie = Movie::find($id);

    if (!$movie) {
        return response()->json(['error' => 'Película no encontrada'], 404);
    }

    // Validar los datos
    $request->validate([
        'title' => 'sometimes|string|max:255',
        'duration' => 'sometimes|integer',
        'genre' => 'sometimes|string|max:255',
        'director' => 'sometimes|string',
        'actors' => 'sometimes|string',
        'sinopsis' => 'sometimes|string',
        'premiere' => 'sometimes|date',
        'room_id' => 'sometimes|exists:rooms,id'
    ]);

    // Actualizar los campos que se han enviado
    $movie->update($request->all());

    return response()->json([
        'message' => 'Película actualizada con éxito',
        'movie' => $movie
    ], 200);
}

// Eliminar una película de la base de datos
public function destroy($id)
{
    $movie = Movie::find($id);

    if (!$movie) {
        return response()->json(['error' => 'Película no encontrada'], 404);
    }

    $movie->delete();

    return response()->json(['message' => 'Película eliminada con éxito'], 200);
}

}
