<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id(); // Esto crea un BIGINT UNSIGNED autoincremental
            $table->string('name');
            $table->timestamps(); // Esto crea 'created_at' y 'updated_at'

        });
        
    }

    public function down()
    {
        Schema::dropIfExists('rooms');
    }
};

