<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComprasTable extends Migration
{
    public function up()
    {
        Schema::create('compras', function (Blueprint $table) {
            $table->id('compra_id');  // Identificador único de la compra
            $table->unsignedBigInteger('user_id');  // Relación con el usuario (clave foránea)
            $table->foreign('user_id')  // Definimos la clave foránea
                  ->references('id')  // Referencia a la columna `id` de la tabla `users`
                  ->on('users')  // Establece la relación con la tabla 'users'
                  ->onDelete('cascade');  // Si se elimina un usuario, se eliminan las compras asociadas
            $table->unsignedBigInteger('seat_id');  // Relación con el asiento (clave foránea)
            $table->foreign('seat_id')  // Definimos la clave foránea
                  ->references('seat_id')  // Referencia a la columna `seat_id` de la tabla `seats`
                  ->on('seats')  // Establece la relación con la tabla 'seats'
                  ->onDelete('cascade');  // Si se elimina un asiento, se eliminan las compras asociadas
            $table->decimal('precio', 8, 2);  // Precio total de la compra (puede ser el precio de varias entradas)
            $table->date('compra_dia');  // Día de la compra
            $table->time('compra_hora');  // Hora de la compra
            $table->string('name')->nullable();  // Nombre del comprador (puede ser nulo)
            $table->string('apellidos')->nullable();  // Apellidos del comprador (puede ser nulo)
            $table->string('email')->nullable();  // Correo electrónico del comprador (puede ser nulo)
            $table->timestamps();  // Fechas de creación y actualización
        });
    }

    public function down()
    {
        Schema::dropIfExists('compras');
    }
}
