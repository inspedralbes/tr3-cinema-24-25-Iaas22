<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateSeatsTable extends Migration
{
    public function up()
    {
        Schema::create('seats', function (Blueprint $table) {
            $table->id('seat_id');  // Identificador único del asiento
            $table->string('row');  // Fila del asiento
            $table->integer('seat_num');  // Número del asiento
            $table->enum('type', ['normal', 'vip']); // Tipo de asiento
            $table->decimal('price', 8, 2); // Precio del asiento
            $table->timestamps();  // Fechas de creación y actualización
        });

        // Inserta precios por defecto con valores fijos
        DB::table('seats')->insert([
            ['row' => 'A', 'seat_num' => 1, 'type' => 'normal', 'price' => 6.00],
            ['row' => 'A', 'seat_num' => 2, 'type' => 'vip', 'price' => 8.00],
            ['row' => 'B', 'seat_num' => 1, 'type' => 'normal', 'price' => 6.00],
            ['row' => 'B', 'seat_num' => 2, 'type' => 'vip', 'price' => 8.00],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('seats');
    }
}
