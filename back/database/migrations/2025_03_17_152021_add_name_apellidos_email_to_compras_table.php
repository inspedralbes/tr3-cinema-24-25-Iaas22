<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNameApellidosEmailToComprasTable extends Migration
{
    public function up()
    {
        Schema::table('compras', function (Blueprint $table) {
            $table->string('name')->after('movie_id'); // Añade la columna name después de movie_id
            $table->string('apellidos')->after('name'); // Añade la columna apellidos después de name
            $table->string('email')->unique()->after('apellidos'); // Añade la columna email después de apellidos
        });
    }

    public function down()
    {
        Schema::table('compras', function (Blueprint $table) {
            $table->dropColumn(['name', 'apellidos', 'email']); // Elimina las columnas en caso de rollback
        });
    }
}
