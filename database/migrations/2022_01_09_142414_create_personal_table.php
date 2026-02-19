<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personal', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',70);
            $table->string('telefono',10)->nullable();
            $table->string('direccion',50)->nullable();
            $table->string('descripcion',100)->nullable();
            $table->boolean('estado')->default(1);
            $table->foreignId('id_cargo');
            $table->foreign('id_cargo')->references('id')->on('cargo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personal');
    }
}
