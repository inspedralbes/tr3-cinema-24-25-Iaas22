<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoviesTable extends Migration
{
    public function up()
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id('movie_id');  // Identificador único de la película
            $table->string('title');  // Título de la película
            $table->string('genre');  // Género de la película
            $table->integer('duration');  // Duración de la película (en minutos)
            $table->string('director');  // Nombre del director
            $table->string('actors');  // Actores principales
            $table->text('synopsis');  // Sinopsis de la película
            $table->date('release_date');  // Fecha de estreno
            $table->string('img')->nullable();  // Imagen (URL o ruta de la imagen de la película)
            $table->timestamps();  // Fechas de creación y actualización de la película
        });
    }

    public function down()
    {
        Schema::dropIfExists('movies');
    }
}
