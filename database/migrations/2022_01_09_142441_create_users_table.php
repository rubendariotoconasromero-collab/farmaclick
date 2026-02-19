<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('matricula',20)->nullable();
            $table->string('email')->nullable();
            $table->string('password');
            $table->boolean('estado')->default(1);
            $table->foreignId('id_grupo');
            $table->foreignId('id_personal');
            $table->foreign('id_grupo')->references('id')->on('grupo');
            $table->foreign('id_personal')->references('id')->on('personal');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
