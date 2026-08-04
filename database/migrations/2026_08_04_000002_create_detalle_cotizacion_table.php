<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalleCotizacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_cotizacion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_cotizacion');
            $table->foreignId('id_producto');
            $table->integer('cantidad');
            $table->decimal('costo_venta', 11, 2)->default(0);
            $table->decimal('sub_total', 11, 2)->default(0);
            $table->string('tiempo_entrega', 100)->nullable();

            $table->foreign('id_cotizacion')->references('id')->on('cotizacion');
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
        Schema::dropIfExists('detalle_cotizacion');
    }
}
