<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MovieController extends Controller
{
    private $apiKey;

    public function __construct()
    {
        $this->apiKey = env('OMDB_API_KEY', '19f8a30e'); // Cargar clave desde .env o usar una por defecto
    }

    // Buscar películas en OMDb
    public function index(Request $request)
    {
        $query = $request->query('query'); // Capturar el término de búsqueda

        if (!$query) {
            return response()->json(['error' => 'Debe ingresar un término de búsqueda'], 400);
        }

        // Petición a OMDb con verificación SSL deshabilitada
        $response = Http::withOptions(['verify' => false])->get("https://www.omdbapi.com/", [
            's' => $query,
            'apikey' => $this->apiKey
        ]);

        $data = $response->json();

        if (isset($data['Response']) && $data['Response'] === 'True') {
            return response()->json($data['Search'], 200);
        }

        return response()->json(['error' => 'No se encontraron películas'], 404);
    }

    // Obtener detalles de una película por su IMDB ID
    public function show($imdbID)
    {
        // Petición a OMDb con verificación SSL deshabilitada
        $response = Http::withOptions(['verify' => false])->get("https://www.omdbapi.com/", [
            'i' => $imdbID,
            'apikey' => $this->apiKey
        ]);

        $data = $response->json();

        if (isset($data['Response']) && $data['Response'] === 'True') {
            return response()->json($data, 200);
        }

        return response()->json(['error' => 'Película no encontrada'], 404);
    }
}
