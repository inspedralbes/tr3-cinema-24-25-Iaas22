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

    }

    public function down()
    {
        Schema::dropIfExists('seats');
    }
}
