<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lote', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_vecimiento');
            $table->decimal('cantidad',11,2)->default(0);
            $table->string('lote',500)->nullable();
            $table->boolean('estado')->default(1);
            $table->foreignId('id_producto');
            $table->foreign('id_producto')->references('id')->on('tienda_articulo');       
         });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lote');
    }
}
