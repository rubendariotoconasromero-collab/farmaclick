<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTiendaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tienda', function (Blueprint $table) {
            $table->id();
            $table->string('cod_tienda',10);
            $table->string('nombre',70);
            $table->string('direccion',50)->nullable();
            $table->string('telefono',10)->nullable();
            $table->string('cod_almacen',10)->nullable();
            $table->boolean('estado')->default(1);
            $table->string('foto',250)->nullable();
            
            $table->foreignId('id_mi_empresa');
            $table->foreign('id_mi_empresa')->references('id')->on('mi_empresa');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tienda');
    }
}
