<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdenServicioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orden_servicio', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->decimal('sub_total',11,2)->default(0);
            $table->decimal('descuento',11,2)->default(0);
            $table->decimal('total',11,2)->default(0);
            $table->string('estado',30);
            $table->string('descripcion',100)->nullable();
            $table->foreignId('id_cliente');
            $table->foreignId('id_personal');
            $table->foreignId('id_usuario');
            $table->foreignId('id_tienda');
            $table->foreign('id_cliente')->references('id')->on('cliente');
            $table->foreign('id_personal')->references('id')->on('personal');
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
        Schema::dropIfExists('orden_servicio');
    }
}
