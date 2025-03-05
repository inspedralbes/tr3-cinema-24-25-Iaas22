<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['reserved', 'available']);
            // Agregamos la columna movie_id que será una clave foránea
            $table->foreignId('movie_id')->constrained('movies')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('seats');
    }
};
