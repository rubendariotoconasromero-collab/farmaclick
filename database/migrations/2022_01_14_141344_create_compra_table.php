<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compra', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->decimal('sub_total',11,2)->default(0);
            $table->decimal('descuento',11,2)->default(0);
            $table->decimal('total',11,2)->default(0);
            $table->string('estado',30);
            $table->string('descripcion',100)->nullable();
            $table->foreignId('id_proveedor');
            $table->foreignId('id_usuario');
            $table->foreignId('id_tipo_pago');
            $table->foreignId('id_forma_pago');
            $table->foreign('id_proveedor')->references('id')->on('proveedor');
            $table->foreign('id_usuario')->references('id')->on('users');
            $table->foreign('id_tipo_pago')->references('id')->on('tipo_pago');
            $table->foreign('id_forma_pago')->references('id')->on('forma_pago');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('compra');
    }
}
