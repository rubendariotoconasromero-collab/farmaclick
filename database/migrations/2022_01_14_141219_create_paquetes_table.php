<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaquetesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paquetes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',150);
            $table->date('fecha_inicio');
            $table->date('fecha_final');
            $table->string('descripcion',5000)->nullable();
            $table->decimal('sub_total',11,2)->default(0);
            $table->decimal('descuento',11,2)->default(0);
            $table->decimal('total',11,2)->default(0);
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
        Schema::dropIfExists('paquetes');
    }
}
