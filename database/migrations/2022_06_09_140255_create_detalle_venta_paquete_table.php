<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalleVentaPaqueteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_venta_paquete', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_venta');
            $table->foreignId('id_paquete');
            $table->integer('cantidad');
            $table->decimal('costo_venta',11,2)->default(0);
            $table->decimal('sub_total',11,2)->default(0);
            $table->foreign('id_venta')->references('id')->on('venta');
            $table->foreign('id_paquete')->references('id')->on('paquetes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_venta_paquete');
    }
}
