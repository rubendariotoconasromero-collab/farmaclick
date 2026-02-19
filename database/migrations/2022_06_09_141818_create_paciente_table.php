<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePacienteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paciente', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',100);
            $table->string('especie',150)->nullable();
            $table->string('edad',70)->nullable();
            $table->string('color',70)->nullable();
            $table->string('raza',70)->nullable();
            $table->boolean('sexo')->default(1);
            $table->string('peso',50)->nullable();
            $table->string('cirugias',1000)->nullable();
            $table->string('enfermedades',1000)->nullable();
            $table->string('vacunas',1000)->nullable();
            $table->boolean('estado')->default(1);
            $table->foreignId('id_cliente');
            $table->foreignId('id_animal');
            $table->foreign('id_cliente')->references('id')->on('cliente');
            $table->foreign('id_animal')->references('id')->on('animal');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('paciente');
    }
}
