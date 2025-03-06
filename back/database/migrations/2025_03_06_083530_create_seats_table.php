<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeatsTable extends Migration
{
    public function up()
    {
        Schema::create('seats', function (Blueprint $table) {
            $table->id('seat_id');  // Identificador único del asiento
            $table->unsignedBigInteger('session_id');  // Relación con la sesión (clave foránea)
            $table->foreign('session_id')  // Definimos la clave foránea
                  ->references('session_id')  // Referencia a la columna `session_id` de la tabla `session`
                  ->on('session')  // Establece la relación con la tabla 'session'
                  ->onDelete('cascade');  // Si se elimina una sesión, se eliminan los asientos asociados
            $table->boolean('is_vip');  // Indica si el asiento es VIP (sí/no)
            $table->string('row');  // Fila del asiento
            $table->integer('seat_num');  // Número del asiento
            $table->enum('status', ['reservado', 'no reservado']);  // Estado del asiento (reservado o no reservado)
            $table->timestamps();  // Fechas de creación y actualización del asiento
        });
    }

    public function down()
    {
        Schema::dropIfExists('seats');
    }
}
?>
