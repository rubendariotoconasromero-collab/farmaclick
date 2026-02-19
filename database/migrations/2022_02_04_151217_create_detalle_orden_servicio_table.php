<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalleOrdenServicioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_orden_servicio', function (Blueprint $table) {
            $table->foreignId('id_orden_servicio');
            $table->foreignId('id_producto');
            $table->integer('cantidad');
            $table->decimal('costo_venta',11,2)->default(0);
            $table->decimal('sub_total',11,2)->default(0);
            $table->primary(['id_orden_servicio', 'id_producto']);
            $table->foreign('id_orden_servicio')->references('id')->on('orden_servicio');
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
        Schema::dropIfExists('detalle_orden_servicio');
    }
}
