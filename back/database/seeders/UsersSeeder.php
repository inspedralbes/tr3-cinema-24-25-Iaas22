<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersSeeder extends Seeder
{
    public function run()
    {
        // Usuario normal 
        User::create([
            'name' => 'ias',
            'email' => 'ias@gmail.com',
            'password' => bcrypt('123456789'),
            'birthday' => '1990-05-15',
            'created_at' => now(),
            'updated_at' => now(),
            'role' => 'user'
        ]);

        // Usuario administrador
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123456789'), 
            'birthday' => '1985-01-01',
            'created_at' => now(),
            'updated_at' => now(),
            'role' => 'admin' 
        ]);
    }
}
