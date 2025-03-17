<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMovieIdToComprasTable extends Migration
{
    public function up()
    {
        Schema::table('compras', function (Blueprint $table) {
            $table->unsignedBigInteger('movie_id')->after('seat_id'); // Añadir columna movie_id
            $table->foreign('movie_id') // Definir clave foránea
                ->references('movie_id') // Hace referencia a movie_id en movies
                ->on('movies') // Tabla movies
                ->onDelete('cascade'); // Eliminar en cascada si se elimina la película
        });
    }

    public function down()
    {
        Schema::table('compras', function (Blueprint $table) {
            $table->dropForeign(['movie_id']); // Eliminar clave foránea
            $table->dropColumn('movie_id'); // Eliminar columna
        });
    }
}
