<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    public function up()
    {
        Schema::create('reservas', function (Blueprint $table) {
            $table->id('reserva_id'); // Identificador único de la reserva
            $table->unsignedBigInteger('seat_id'); // Relación con la butaca
            $table->unsignedBigInteger('session_id'); // Relación con la sesión
            $table->unsignedBigInteger('user_id')->nullable(); // Relación con el usuario (opcional)
            $table->unsignedBigInteger('entrada_id'); // Relación con el tipo de entrada

            // Claves foráneas
            $table->foreign('seat_id')
                  ->references('seat_id')
                  ->on('seats')
                  ->onDelete('cascade');

            $table->foreign('session_id')
                  ->references('session_id')
                  ->on('session')
                  ->onDelete('cascade');

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->foreign('entrada_id')
                  ->references('entrada_id')
                  ->on('entradas')
                  ->onDelete('cascade');

            $table->enum('status', ['reservada', 'no reservada']); // Estado de la reserva
            $table->timestamps(); // Fechas de creación y actualización
        });
    }

    public function down()
    {
        Schema::dropIfExists('reservas');
    }
}
