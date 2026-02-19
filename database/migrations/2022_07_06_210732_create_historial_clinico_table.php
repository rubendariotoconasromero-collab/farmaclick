<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistorialClinicoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historial_clinico', function (Blueprint $table) {
            $table->id();
            $table->string('nro_historia',100)->nullable();
            $table->foreignId('id_cliente');
            $table->foreignId('id_paciente');
            $table->foreignId('id_usuario');
            $table->foreign('id_cliente')->references('id')->on('cliente');
            $table->foreign('id_paciente')->references('id')->on('paciente');
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
        Schema::dropIfExists('historial_clinico');
    }
}
