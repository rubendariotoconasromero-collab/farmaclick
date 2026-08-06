<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalleHistoriaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_historia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_historia');
            $table->foreignId('id_personal');
            $table->foreignId('id_paciente');
            $table->decimal('peso',11,2)->default(0);
            $table->string('edad',70)->nullable();
            $table->integer('meses')->nullable();
            $table->date('fecha');
            $table->string('motivo',4000)->nullable();
            $table->boolean('estado')->default(0);
            $table->foreign('id_historia')->references('id')->on('historial_clinico');
            $table->foreign('id_personal')->references('id')->on('personal');
            $table->foreign('id_paciente')->references('id')->on('paciente');
            //INICIO VACUNA
            // //PERRO
            $table->boolean('parvovirus')->default(0);
            $table->boolean('hexavalente')->default(0);
            $table->boolean('octavalente')->default(0);
            $table->boolean('rabia_perro')->default(0);
            $table->boolean('tos_perrera')->default(0);
            $table->boolean('ninguna_perro')->default(0);
            $table->boolean('obs_p')->default(0);
            $table->string('obs_perro',500)->nullable();
            // //GATO
            $table->boolean('triple_felina')->default(0);
            $table->boolean('rabia_gato')->default(0);
            $table->boolean('ninguna_gato')->default(0);
            $table->boolean('obs_g')->default(0);
            $table->string('obs_gato',500)->nullable();
            //Desparacitacion
            $table->boolean('desparacitacion')->default(0);
            $table->string('desparacitacion_cuando',500)->nullable();
            // //FIN VACUNAS

            // //TEMPERATURA
            $table->decimal('temperatura',11,2)->default(0);
            //F.C.
            $table->decimal('fc',11,2)->default(0);
            $table->boolean('taquicardia')->default(0);
            $table->boolean('arritmia')->default(0);
            $table->boolean('bradicardia')->default(0);
            $table->boolean('sin_alteracion')->default(0);

            //F.R.
            $table->decimal('fr',11,2)->default(0);
            $table->boolean('bueno_fr')->default(0);
            $table->boolean('disnea')->default(0);


            //Mucosa
            $table->boolean('rosada')->default(0);
            $table->boolean('palidas')->default(0);
            $table->boolean('ictericas')->default(0);
            $table->boolean('cianotica')->default(0);
            
            //Apetito
            $table->boolean('normal_apetito')->default(0);
            $table->boolean('disminuido')->default(0);
            $table->boolean('anorexico')->default(0);
            
            //Mucosa
            $table->boolean('normal_mucosa')->default(0);
            $table->boolean('leve')->default(0);
            $table->boolean('moderada')->default(0);
            $table->boolean('marcada')->default(0);
            
            //Estado General
            $table->boolean('bueno_estado')->default(0);
            $table->boolean('regular')->default(0);
            $table->boolean('malo')->default(0);
            
            //Antecedentes de Enfermedades
            $table->boolean('enfermedades')->default(0);
            $table->string('enfermedades_cuales',500)->nullable();
            $table->string('enfermedades_cuando',500)->nullable();

            //Antecedentes de cirugia
            $table->boolean('cirugia')->default(0);
            $table->string('cirugia_cuales',500)->nullable();
            $table->string('cirugia_cuando',500)->nullable();

            //Organos de Sentido
            $table->string('ocular',500)->nullable();
            $table->string('nariz',500)->nullable();
            $table->string('bucal',500)->nullable();
            $table->string('piel_anexo',500)->nullable();
            $table->string('oidos',500)->nullable();
            $table->string('vulvar',500)->nullable();
            $table->string('prepucial',500)->nullable();

            //Aparato Digestivo
            $table->boolean('digestivo_sin_alteracion')->default(0);
            $table->text('digestivo_obs')->nullable();

            //Aparato Respiratorio
            $table->boolean('respiratorio_sin_alteracion')->default(0);
            $table->text('respiratorio_obs')->nullable();

            //Aparato Genito Urinario
            $table->boolean('urinario_sin_alteracion')->default(0);
            $table->text('urinario_obs')->nullable();
            
            //Aparato Nervioso
            $table->boolean('nervioso_sin_alteracion')->default(0);
            $table->text('nervioso_obs')->nullable();

            // //EXAMENES COMPLEMENTARIO
            $table->text('muestra')->nullable();
            $table->text('examenes_solicitado')->nullable();

            //TRATAMIENTO INDICADO
            //1er_dia
            $table->date('fecha1');
            $table->time('hora1');
            $table->decimal('t1',11,2)->default(0);
            $table->string('dr1',100)->nullable();
            $table->decimal('costo1',11,2)->default(0);
            $table->string('observaciones1',500)->nullable();
            $table->text('primer_dia')->nullable();

            //HIDRATACION NUEVO ATRIBUTO
            $table->text('hidratacion')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_historia');
    }
}
