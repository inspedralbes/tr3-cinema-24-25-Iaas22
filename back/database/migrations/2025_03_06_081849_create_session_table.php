<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionTable extends Migration
{
    public function up()
    {
        Schema::create('session', function (Blueprint $table) {
            $table->id('session_id');  
            $table->unsignedBigInteger('movie_id'); 
            $table->foreign('movie_id') 
                  ->references('movie_id')
                  ->on('movies')  
                  ->onDelete('cascade'); 
            $table->time('session_time');
            $table->date('session_date');
            $table->boolean('special_day');
            $table->timestamps();  
        });
    }

    public function down()
    {
        Schema::dropIfExists('session');
    }
}
