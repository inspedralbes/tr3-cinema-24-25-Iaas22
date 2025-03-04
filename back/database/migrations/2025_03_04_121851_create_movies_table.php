<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up() {
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('duration');
            $table->string('genre');
            $table->text('director');
            $table->text('actors');
            $table->text('sinopsis');
            $table->timestamp('premiere');
            $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
        });
    }

    public function down() {
        Schema::dropIfExists('movies');
    }
};