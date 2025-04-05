<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateSeatsTable extends Migration
{
    public function up()
    {
        Schema::create('seats', function (Blueprint $table) {
            $table->id('seat_id');  
            $table->string('row'); 
            $table->integer('seat_num'); 
            $table->enum('type', ['normal', 'vip']); 
            $table->decimal('price', 8, 2); 
            $table->timestamps();  
        });

    }

    public function down()
    {
        Schema::dropIfExists('seats');
    }
}
