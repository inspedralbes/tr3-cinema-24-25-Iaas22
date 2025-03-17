<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNameApellidosEmailToComprasTable extends Migration
{
    public function up()
    {
        Schema::table('compras', function (Blueprint $table) {
            if (!Schema::hasColumn('compras', 'name')) {
                $table->string('name')->after('movie_id')->nullable(); // Permitir nulos para evitar conflictos
            }

            if (!Schema::hasColumn('compras', 'apellidos')) {
                $table->string('apellidos')->after('name')->nullable();
            }

            if (!Schema::hasColumn('compras', 'email')) {
                $table->string('email')->nullable(); // Sin 'unique' para evitar conflictos
            } else {
                // Si existe y es única, elimina la restricción
                $table->dropUnique('compras_email_unique');
                $table->string('email')->nullable()->change(); // Permitir nulos
            }
        });
    }

    public function down()
    {
        Schema::table('compras', function (Blueprint $table) {
            if (Schema::hasColumn('compras', 'name')) {
                $table->dropColumn('name');
            }

            if (Schema::hasColumn('compras', 'apellidos')) {
                $table->dropColumn('apellidos');
            }

            if (Schema::hasColumn('compras', 'email')) {
                $table->dropColumn('email');
            }
        });
    }
}
