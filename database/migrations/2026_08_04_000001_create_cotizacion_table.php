<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCotizacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cotizacion', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->date('fecha_venci')->nullable();
            $table->integer('dias_credito')->nullable();
            $table->string('tiempo_entrega', 100)->nullable();
            $table->string('lugar_entrega', 150)->nullable();
            $table->decimal('sub_total', 11, 2)->default(0);
            $table->decimal('descuento', 11, 2)->default(0);
            $table->decimal('total', 11, 2)->default(0);
            $table->string('estado', 30);
            $table->string('tipo_venta', 30);
            $table->text('nota')->nullable();
            $table->boolean('con_factura')->nullable();
            $table->foreignId('id_cliente')->nullable();
            $table->foreignId('id_tipo_pago');
            $table->foreignId('id_forma_pago')->nullable();
            $table->foreignId('id_usuario');
            $table->foreignId('id_tienda');

            $table->foreign('id_cliente')->references('id')->on('cliente');
            $table->foreign('id_tipo_pago')->references('id')->on('tipo_pago');
            $table->foreign('id_forma_pago')->references('id')->on('forma_pago');
            $table->foreign('id_usuario')->references('id')->on('users');
            $table->foreign('id_tienda')->references('id')->on('tienda');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cotizacion');
    }
}
