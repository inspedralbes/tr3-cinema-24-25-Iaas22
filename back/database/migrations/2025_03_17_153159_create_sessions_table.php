<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Ejecutar la migración.
     */
    public function up(): void
    {
        Schema::create('sessions', function (Blueprint $table) {
            $table->id(); 
            $table->string('user_id')->nullable(); 
            $table->string('ip_address')->nullable(); 
            $table->text('user_agent')->nullable(); 
            $table->longText('payload'); 
            $table->integer('last_activity'); 

            $table->timestamps(); 
        });
    }

    /**
     * Revertir la migración.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
    }
};
