<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalleVacunaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_vacuna', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_animal');
            $table->foreignId('id_producto');
            $table->string('tipo_vacuna',100);
            $table->foreign('id_animal')->references('id')->on('animal');
            $table->foreign('id_producto')->references('id')->on('tienda_articulo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_vacuna');
    }
}
