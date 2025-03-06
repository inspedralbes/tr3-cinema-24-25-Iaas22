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


public function show($id)
{
    try {
        $movie = Movie::findOrFail($id); // Encuentra la película por ID
        return response()->json($movie); // Devuelve los detalles de la película en formato JSON
    } catch (\Exception $e) {
        return response()->json(['error' => 'Película no encontrada'], 404);
    }
}


    

    // Crear una nueva película en la base de datos
    public function store(Request $request)
    {
        try {
            // Validar los datos
            $request->validate([
                'title' => 'required|string|max:255',
                'genre' => 'required|string|max:255',
                'duration' => 'required|integer',
                'director' => 'required|string|max:255',
                'actors' => 'required|string|max:255',
                'synopsis' => 'required|string',
                'release_date' => 'required|date',
                'img' => 'nullable|string', // Imagen es opcional
            ]);

            // Crear la película en la base de datos
            $movie = Movie::create([
                'title' => $request->title,
                'genre' => $request->genre,
                'duration' => $request->duration,
                'director' => $request->director,
                'actors' => $request->actors,
                'synopsis' => $request->synopsis,
                'release_date' => $request->release_date,
                'img' => $request->img, // Imagen es opcional
            ]);

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
            'genre' => 'sometimes|string|max:255',
            'duration' => 'sometimes|integer',
            'director' => 'sometimes|string|max:255',
            'actors' => 'sometimes|string|max:255',
            'synopsis' => 'sometimes|string',
            'release_date' => 'sometimes|date',
            'img' => 'nullable|string',
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
