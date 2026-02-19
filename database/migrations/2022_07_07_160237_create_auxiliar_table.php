<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuxiliarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auxiliar', function (Blueprint $table) {
            $table->id();
            $table->decimal('cantidad',11,2)->default(0);
            $table->foreignId('id_lote');
            $table->foreignId('id_venta');
            $table->foreign('id_lote')->references('id')->on('lote');
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
        Schema::dropIfExists('auxiliar');
    }
}
