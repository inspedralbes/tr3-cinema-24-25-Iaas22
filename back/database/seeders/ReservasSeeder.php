<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reserva;  // Importa el modelo correctamente
use App\Models\Seat;
use App\Models\Session;
use App\Models\User;
use App\Models\Entrada;

class ReservasSeeder extends Seeder
{
    public function run()
    {
        // Obtener todos los registros necesarios
        $seats = Seat::all();
        $sessions = Session::all();
        $users = User::all();
        $entradas = Entrada::all();

        // Asegúrate de tener al menos un registro en las tablas
        if ($seats->isEmpty() || $sessions->isEmpty() || $users->isEmpty() || $entradas->isEmpty()) {
            $this->command->info('No hay suficientes registros en las tablas requeridas.');
            return;
        }

        // Crear reservas de ejemplo
        for ($i = 0; $i < 10; $i++) {
            Reserva::create([
                'seat_id' => $seats->random()->seat_id,       // Selecciona un asiento aleatorio
                'session_id' => $sessions->random()->session_id, // Selecciona una sesión aleatoria
                'user_id' => $users->random()->id,            // Selecciona un usuario aleatorio
                'entrada_id' => $entradas->random()->entrada_id, // Selecciona una entrada aleatoria
                'status' => 'disponible', // Cambiado a un valor válido del ENUM: 'pendiente', 'confirmada', o 'cancelada'
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
