<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAntiparasitarioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('antiparasitario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_paciente');
            $table->date('fecha');
            $table->date('prox_fecha');
            $table->decimal('sub_total',11,2)->default(0);
            $table->decimal('descuento',11,2)->default(0);
            $table->decimal('total',11,2)->default(0);
            $table->string('estado',30);
            $table->foreign('id_paciente')->references('id')->on('paciente');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('antiparasitario');
    }
}
