<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalleVentaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_venta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_venta');
            $table->foreignId('id_producto');
            $table->integer('cantidad');
            $table->decimal('costo_venta',11,2)->default(0);
            $table->decimal('sub_total',11,2)->default(0);
            $table->foreignId('id_lote');
            $table->foreign('id_venta')->references('id')->on('venta');
            $table->foreign('id_producto')->references('id')->on('tienda_articulo');
            $table->foreign('id_lote')->references('id')->on('lote');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_venta');
    }
}
