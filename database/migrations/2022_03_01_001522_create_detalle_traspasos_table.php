<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalleTraspasosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_traspasos', function (Blueprint $table) {
            $table->foreignId('id_tienda_articulo');
            $table->foreignId('id_traspaso');
            $table->integer('cantidad')->default(0);
            $table->primary(['id_tienda_articulo','id_traspaso']);
            $table->foreign('id_tienda_articulo')->references('id')->on('tienda_articulo');
            $table->foreign('id_traspaso')->references('id')->on('traspasos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_traspasos');
    }
}
