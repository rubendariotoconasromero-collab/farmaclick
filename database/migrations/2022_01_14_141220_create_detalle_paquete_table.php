<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetallePaqueteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_paquete', function (Blueprint $table) {
            $table->foreignId('id_paquete');
            $table->foreignId('id_producto');
            $table->integer('cantidad');
            $table->decimal('costo_venta',11,2)->default(0);
            $table->decimal('sub_total',11,2)->default(0);
            $table->primary(['id_paquete', 'id_producto']);
            $table->foreign('id_paquete')->references('id')->on('paquetes');
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
        Schema::dropIfExists('detalle_paquete');
    }
}
