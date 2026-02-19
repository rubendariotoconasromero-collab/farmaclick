<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalleControlVacunaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_control_vacuna', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_control_vacuna');
            $table->foreignId('id_producto');
            $table->date('fecha');
            $table->date('prox_fecha');
            $table->integer('cantidad');
            $table->string('edad',70)->nullable();
            $table->decimal('costo_venta',11,2)->default(0);
            $table->decimal('sub_total',11,2)->default(0);
            $table->boolean('estado')->default(1);
            $table->foreign('id_control_vacuna')->references('id')->on('control_vacuna');
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
        Schema::dropIfExists('detalle_control_vacuna');
    }
}
