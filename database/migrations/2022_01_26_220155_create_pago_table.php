<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pago', function (Blueprint $table) {
            $table->integer('id');
            $table->date('fecha');
            $table->date('fecha_final');
            $table->decimal('monto',11,2)->default(0);
            $table->decimal('saldo',11,2)->nullable();
            $table->string('descripcion',100)->nullable();
            $table->boolean('estado')->default(1);
            $table->foreignId('id_tipo_pago')->default(1);
            $table->foreignId('id_venta');
            $table->primary('id');
            $table->foreign('id_tipo_pago')->references('id')->on('tipo_pago');
            $table->foreign('id_venta')->references('id')->on('venta');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pago');
    }
}
