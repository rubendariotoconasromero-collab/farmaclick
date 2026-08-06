<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCXPagarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('c_x_pagar', function (Blueprint $table) {
            $table->id();
            $table->integer('id_pago');
            $table->date('fecha');
            $table->decimal('monto_total',11,2)->default(0);
            $table->decimal('amortizacion',11,2)->default(0);
            $table->decimal('saldo',11,2)->default(0);
            $table->string('descripcion',100)->nullable();
            $table->integer('id_forma_pago')->default(0);
            $table->integer('control')->default(0);
            $table->integer('id_usuario')->default(0);
            $table->foreign('id_pago')->references('id')->on('pago_compra');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('c_x_pagar');
    }
}
