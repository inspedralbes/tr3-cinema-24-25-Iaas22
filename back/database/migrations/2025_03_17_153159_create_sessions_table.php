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
            $table->id(); // session_id como clave primaria auto incremental
            $table->string('user_id')->nullable(); // ID del usuario (nullable para sesiones no autenticadas)
            $table->string('ip_address')->nullable(); // Dirección IP del cliente
            $table->text('user_agent')->nullable(); // Info del navegador/dispositivo
            $table->longText('payload'); // Datos de la sesión serializados
            $table->integer('last_activity'); // Timestamp de la última actividad

            $table->timestamps(); // created_at y updated_at
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
