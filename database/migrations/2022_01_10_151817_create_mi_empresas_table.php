<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMiEmpresasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mi_empresa', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',100)->nullable();
            $table->string('nit',20)->nullable();
            $table->string('representante',100)->nullable();
            $table->string('direccion',200)->nullable();
            $table->string('telefono',50)->nullable();
            $table->string('descripcion',200)->nullable();
            $table->string('localidad',200)->nullable();
            $table->string('Correo',200)->nullable();
            $table->string('sitio_web',200)->nullable();
            $table->string('foto',250)->nullable();
            $table->string('logo_login',250)->nullable();
            $table->string('logo_sistema',250)->nullable();
            $table->string('logo_usuario',250)->nullable();
            $table->string('fondo_login',250)->nullable();
            $table->string('color_login',8)->nullable();
            $table->string('color_menu',8)->nullable();
            $table->boolean('estado')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mi_empresa');
    }
}
