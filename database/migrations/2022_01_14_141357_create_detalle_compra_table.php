<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalleCompraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_compra', function (Blueprint $table) {
            $table->foreignId('id_compra');
            $table->foreignId('id_producto');
            $table->integer('cantidad');
            $table->decimal('costo_compra',11,2)->default(0);
            $table->decimal('sub_total',11,2)->default(0);
            $table->decimal('descuento',11,2)->default(0);
            $table->integer('eliminado')->default(0);
            $table->primary(['id_compra', 'id_producto']);
            $table->foreignId('id_lote');
            $table->foreign('id_compra')->references('id')->on('compra');
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
        Schema::dropIfExists('detalle_compra');
    }
}
