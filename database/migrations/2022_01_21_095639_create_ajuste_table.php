<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAjusteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ajuste', function (Blueprint $table) {
            $table->id();
            $table->integer('stock')->default(0);
            $table->decimal('costo_compra',11,2)->default(0);
            $table->decimal('costo_venta',11,2)->nullable();
            $table->integer('stock_anterior')->default(0);
            $table->integer('stock_actual')->default(0);
            $table->decimal('costo_unitario',11,2)->default(0);
            $table->decimal('costo_mayorista',11,2)->default(0);
            $table->decimal('costo_preferencial',11,2)->default(0);
            $table->string('observacion',100)->nullable();
            $table->foreignId('id_lote');
            $table->foreignId('id_motivo_ajuste');
            $table->foreign('id_lote')->references('id')->on('lote');
            $table->foreign('id_motivo_ajuste')->references('id')->on('motivo_ajuste');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ajuste');
    }
}
