<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RemoveLegacyPermissionSystem extends Migration
{
    public function up()
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS permisoUsuario');
        Schema::dropIfExists('usuario_permisos');
        Schema::dropIfExists('detalle_forms');
        Schema::dropIfExists('permiso_forms');
        Schema::dropIfExists('formularios');
        Schema::dropIfExists('grupo_permiso');
        Schema::dropIfExists('permiso');
    }

    public function down()
    {
        $audit = DB::table('rbac_migration_audits')
            ->where('source', 'legacy-rbac-before-migration')->latest('id')->first();
        $snapshot = $audit ? json_decode($audit->payload, true) : [];

        Schema::create('permiso', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 70);
            $table->string('tipo_permiso', 30);
        });
        Schema::create('formularios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 70);
        });
        Schema::create('grupo_permiso', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_grupo')->constrained('grupo');
            $table->foreignId('id_permiso')->constrained('permiso');
        });
        Schema::create('permiso_forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_grupo')->constrained('grupo');
        });
        Schema::create('detalle_forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_permiso_form')->constrained('permiso_forms');
            $table->foreignId('id_formulario')->constrained('formularios');
        });
        Schema::create('usuario_permisos', function (Blueprint $table) {
            $table->foreignId('id_usuario')->constrained('users');
            $table->foreignId('id_permiso')->constrained('detalle_forms');
        });

        foreach (['permiso', 'formularios', 'grupo_permiso', 'permiso_forms', 'detalle_forms', 'usuario_permisos'] as $table) {
            if (!empty($snapshot[$table])) {
                DB::table($table)->insert($snapshot[$table]);
            }
        }

        DB::unprepared('CREATE PROCEDURE permisoUsuario(IN id_usuario BIGINT, IN id_formulario BIGINT)
            BEGIN
                SELECT IF(EXISTS(
                    SELECT 1 FROM usuario_permisos up
                    INNER JOIN detalle_forms df ON df.id = up.id_permiso
                    WHERE up.id_usuario = id_usuario AND df.id_formulario = id_formulario
                ), 1, 0) form;
            END');
    }
}
