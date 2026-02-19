<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalleFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_permiso_form');
            $table->foreignId('id_formulario');
            $table->foreign('id_permiso_form')->references('id')->on('permiso_forms');
            $table->foreign('id_formulario')->references('id')->on('formularios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_forms');
    }
}
