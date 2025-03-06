<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionTable extends Migration
{
    public function up()
    {
        Schema::create('session', function (Blueprint $table) {
            $table->id('session_id');  // Identificador único de la sesión
            $table->unsignedBigInteger('movie_id');  // Relación con la película (clave foránea)
            $table->foreign('movie_id')  // Definimos la clave foránea
                  ->references('movie_id')  // Referencia a la columna `movie_id` de la tabla `movies`
                  ->on('movies')  // Establece la relación con la tabla 'movies'
                  ->onDelete('cascade');  // Si se elimina una película, se eliminan las sesiones asociadas
            $table->time('session_time');  // Hora de la sesión
            $table->date('session_date');  // Fecha de la sesión
            $table->boolean('special_day');  // Indica si es un día especial (sí/no)
            $table->timestamps();  // Fechas de creación y actualización de la sesión
        });
    }

    public function down()
    {
        Schema::dropIfExists('session');
    }
}
