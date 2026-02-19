<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGrupoPermisoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grupo_permiso', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_grupo');
            $table->foreignId('id_permiso');
            $table->foreign('id_grupo')->references('id')->on('grupo');
            $table->foreign('id_permiso')->references('id')->on('permiso');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('grupo_permiso');
    }
}
