<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsuarioPermisosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuario_permisos', function (Blueprint $table) {
            $table->foreignId('id_usuario');
            $table->foreignId('id_permiso');
            $table->foreign('id_usuario')->references('id')->on('users');
            $table->foreign('id_permiso')->references('id')->on('detalle_forms');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuario_permisos');
    }
}
