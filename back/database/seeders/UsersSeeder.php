<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'ejemplo',
            'email' => 'ejemplo.perez@gmail.com',
            'password' => bcrypt('password123'), // Asegúrate de usar un hash de contraseña
            'birthday' => '1990-05-15', // Fecha de nacimiento en formato 'YYYY-MM-DD'
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
