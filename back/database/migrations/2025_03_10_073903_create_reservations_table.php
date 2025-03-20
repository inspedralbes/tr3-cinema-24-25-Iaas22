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
            $table->decimal('precio', 8, 2)->nullable();  // Precio total de la compra (puede ser el precio de varias entradas)
            $table->date('compra_dia')->nullable();  // Día de la compra
            $table->time('compra_hora')->nullable();  // Hora de la compra
            $table->string('name')->nullable();  // Nombre del comprador (puede ser nulo)
            $table->string('apellidos')->nullable();  // Apellidos del comprador (puede ser nulo)
            $table->string('email')->nullable();  // Correo electrónico del comprador (puede ser nulo)
            $table->enum('status', ['reservada', 'confirmada'])->default('reservada'); // Estado de la reserva
            $table->timestamps(); // Fechas de creación y actualización

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
        });
    }

    public function down()
    {
        Schema::dropIfExists('reservas');
    }
}
