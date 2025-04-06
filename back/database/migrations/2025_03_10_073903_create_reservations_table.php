<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    public function up()
    {
        Schema::create('reservas', function (Blueprint $table) {
            $table->id('reserva_id'); 
            $table->unsignedBigInteger('seat_id'); 
            $table->unsignedBigInteger('session_id'); 
            $table->unsignedBigInteger('user_id')->nullable(); 
            $table->decimal('precio', 8, 2)->nullable();  
            $table->date('compra_dia')->nullable(); 
            $table->time('compra_hora')->nullable();  
            $table->string('name')->nullable(); 
            $table->string('apellidos')->nullable();  
            $table->string('email')->nullable();  
            $table->enum('status', ['reservada', 'confirmada'])->default('reservada'); 
            $table->timestamps(); 

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
