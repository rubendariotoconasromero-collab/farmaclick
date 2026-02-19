<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVentaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('venta', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->decimal('sub_total',11,2)->default(0);
            $table->decimal('descuento',11,2)->default(0);
            $table->decimal('total',11,2)->default(0);
            $table->string('estado',30);
            $table->string('tipo_venta',30);
            $table->foreignId('id_cliente')->nullable();
            $table->foreignId('id_tipo_pago');
            $table->foreignId('id_forma_pago');
            $table->foreignId('id_usuario');
            $table->foreignId('id_tienda');
            $table->foreignId('id_orden_servicio')->nullable();
            $table->foreignId('id_paquete')->nullable();

            $table->foreign('id_cliente')->references('id')->on('cliente');
            $table->foreign('id_tipo_pago')->references('id')->on('tipo_pago');
            $table->foreign('id_forma_pago')->references('id')->on('forma_pago');
            $table->foreign('id_usuario')->references('id')->on('users');
            $table->foreign('id_tienda')->references('id')->on('tienda');
            $table->foreign('id_orden_servicio')->references('id')->on('orden_servicio');
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
        Schema::dropIfExists('venta');
    }
}
