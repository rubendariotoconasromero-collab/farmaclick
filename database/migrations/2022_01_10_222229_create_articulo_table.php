<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticuloTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articulo', function (Blueprint $table) {
            $table->id();
            $table->string('cod_sistema',50)->nullable();
            $table->string('cod_proveedor',50)->nullable();
            $table->string('cod_barra',50)->nullable();
            $table->string('nombre_comercial',100)->nullable();
            $table->string('nombre_generico',100)->nullable();
            $table->decimal('costo_compra',11,2)->default(0);
            $table->decimal('costo_compra_caja',11,2)->default(0);
            $table->decimal('costo_unitario',11,2)->default(0);
            $table->decimal('costo_mayorista',11,2)->default(0);
            $table->decimal('costo_preferencial',11,2)->default(0);
            $table->integer('stock_minimo')->default(0)->nullable();
            $table->string('ubicacion',1000)->nullable();
            $table->text('descripcion')->nullable();
            $table->text('composicion')->nullable();
            $table->decimal('venta_presentacion',11,2)->default(0);
            $table->decimal('cantidad_caja',11,2)->default(0);
            $table->decimal('cantidad_blister',11,2)->default(0);
            $table->decimal('precio_caja',11,2)->default(0);
            $table->decimal('precio_blister',11,2)->default(0);
            $table->boolean('psicotropico')->default(1);
            $table->boolean('refrigerado')->default(1);
            $table->boolean('estado')->default(1);
            $table->foreignId('id_categoria')->nullable();
            $table->foreignId('id_marca')->nullable();
            $table->foreignId('id_unidad')->nullable();
            $table->foreignId('id_proveedor')->nullable();
            $table->foreign('id_categoria')->references('id')->on('categoria');
            $table->foreign('id_unidad')->references('id')->on('unidad_medida');
            $table->foreign('id_proveedor')->references('id')->on('proveedor');
            $table->foreign('id_marca')->references('id')->on('marca');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articulo');
    }
}
