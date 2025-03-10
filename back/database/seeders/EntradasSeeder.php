<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Entrada;

class EntradasSeeder extends Seeder
{
    public function run()
    {
        Entrada::create([
            'nombre' => 'normal',
            'precio_normal' => 6.00,
            'precio_vip' => 8.00,
            'precio_espectador' => 4.00,
        ]);
    }
}
