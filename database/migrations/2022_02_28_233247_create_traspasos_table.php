<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTraspasosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('traspasos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->time('hora');
            $table->string('glosa',100)->nullable();
            $table->boolean('estado')->default(1);
            $table->foreignId('id_tienda1');
            $table->foreignId('id_tienda2');
            $table->foreignId('id_usuario');
            $table->foreign('id_tienda1')->references('id')->on('tienda');
            $table->foreign('id_tienda2')->references('id')->on('tienda');
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
        Schema::dropIfExists('traspasos');
    }
}
