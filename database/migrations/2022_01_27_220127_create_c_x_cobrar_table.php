<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCXCobrarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('c_x_cobrar', function (Blueprint $table) {
            $table->id();
            $table->integer('id_pago');
            $table->date('fecha');
            $table->decimal('monto_total',11,2)->default(0);
            $table->decimal('amortizacion',11,2)->default(0);
            $table->decimal('saldo',11,2)->default(0);
            $table->string('descripcion',100)->nullable();
            $table->foreign('id_pago')->references('id')->on('pago');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('c_x_cobrar');
    }
}
