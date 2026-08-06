<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGastoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gasto', function (Blueprint $table) {
            $table->id();
            $table->date('fecha',30);
            $table->decimal('monto',11,2)->default(0);
            $table->string('descripcion',100)->nullable();
            $table->foreignId('id_motivo_gasto');
            $table->integer('id_forma_pago')->nullable();
            $table->integer('id_usuario')->nullable();
            $table->decimal('efectivo',11,2)->nullable();
            $table->decimal('deposito',11,2)->nullable();
            $table->integer('control')->default(0);
            $table->foreign('id_motivo_gasto')->references('id')->on('motivo_gasto');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gasto');
    }
}
