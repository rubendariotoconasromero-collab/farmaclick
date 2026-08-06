<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArqueoCajasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('arqueo_caja', function (Blueprint $table) {
            $table->id();
            $table->datetime('fecha_apertura')->nullable();
            $table->datetime('fecha_cierre')->nullable();
            $table->decimal('doscientos',11,2)->default(0);
            $table->decimal('cien',11,2)->default(0);
            $table->decimal('cincuenta',11,2)->default(0);
            $table->decimal('veinte',11,2)->default(0);
            $table->decimal('diez',11,2)->default(0);
            $table->decimal('cinco',11,2)->default(0);
            $table->decimal('dos',11,2)->default(0);
            $table->decimal('uno',11,2)->default(0);
            $table->decimal('cerocinco',11,2)->default(0);
            $table->decimal('ceroveinte',11,2)->default(0);
            $table->decimal('cien_dolar',11,2)->default(0);
            $table->decimal('registro_venta',11,2)->default(0);
            $table->decimal('apertura',11,2)->default(0);
            $table->decimal('total',11,2)->default(0);
            $table->decimal('gastos',11,2)->default(0);
            $table->decimal('registro_compra',11,2)->default(0);
            $table->decimal('saldo_sistema',11,2)->default(0);
            $table->decimal('saldo_efectivo',11,2)->default(0);
            $table->decimal('diferencia',11,2)->default(0);
            $table->string('estado',20)->nullable();
            $table->decimal('total_contado',11,2)->nullable();
            $table->decimal('total_credito',11,2)->nullable();
            $table->decimal('total_contado_compra',11,2)->nullable();
            $table->decimal('total_credito_compra',11,2)->nullable();
            $table->decimal('total_credito_deposito',11,2)->default(0);
            $table->decimal('total_contado_deposito',11,2)->default(0);
            $table->decimal('gastos_deposito',11,2)->default(0);
            $table->decimal('total_contado_deposito_compra',11,2)->default(0);
            $table->decimal('total_credito_deposito_compra',11,2)->default(0);
            $table->foreignId('id_usuario');
            $table->foreign('id_usuario')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('arqueo_caja');
    }
}
