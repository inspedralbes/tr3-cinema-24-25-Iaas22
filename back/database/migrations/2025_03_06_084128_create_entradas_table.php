<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntradasTable extends Migration
{
    public function up()
    {
        Schema::create('entradas', function (Blueprint $table) {
            $table->id('entrada_id');  // Identificador único de la entrada
            $table->enum('nombre', ['normal', 'vip', 'espectador']);  // Tipo de entrada: normal, vip, espectador
            $table->decimal('precio_normal', 5, 2)->default(6.00);  // Precio para entrada normal
            $table->decimal('precio_vip', 5, 2)->default(8.00);  // Precio para entrada VIP
            $table->decimal('precio_espectador', 5, 2)->default(4.00);  // Precio para entrada espectador (normal: 4, VIP: 6)
            $table->timestamps();  // Fechas de creación y actualización
        });
    }

    public function down()
    {
        Schema::dropIfExists('entradas');
    }
}
?>
