<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveSessionIdFromSeatsTable extends Migration
{
    public function up()
    {
        Schema::table('seats', function (Blueprint $table) {
            // Si todavía existe la columna session_id, elimínala
            if (Schema::hasColumn('seats', 'session_id')) {
                $table->dropForeign(['session_id']);
                $table->dropColumn('session_id');
            }
        });
    }

    public function down()
    {
        Schema::table('seats', function (Blueprint $table) {
            // Si necesitas restaurar la columna en el rollback
            if (!Schema::hasColumn('seats', 'session_id')) {
                $table->unsignedBigInteger('session_id')->nullable();
                $table->foreign('session_id')->references('session_id')->on('session')->onDelete('cascade');
            }
        });
    }
}
