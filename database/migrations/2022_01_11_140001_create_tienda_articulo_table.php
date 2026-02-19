<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTiendaArticuloTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tienda_articulo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_articulo');
            $table->foreignId('id_tienda');
            $table->integer('stock')->default(0);
            $table->foreign('id_articulo')->references('id')->on('articulo');
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
        Schema::dropIfExists('tienda_articulo');
    }
}
