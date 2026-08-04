<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CreateNativeRbacTables extends Migration
{
    public function up()
    {
        Schema::table('grupo', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('nombre');
            $table->boolean('is_super_admin')->default(false)->after('estado');
            $table->unique('slug', 'grupo_slug_unique');
        });

        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('name');
            $table->string('module');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('group_permission', function (Blueprint $table) {
            $table->unsignedBigInteger('id_grupo');
            $table->unsignedBigInteger('permission_id');
            $table->primary(['id_grupo', 'permission_id']);
            $table->foreign('id_grupo')->references('id')->on('grupo')->cascadeOnDelete();
            $table->foreign('permission_id')->references('id')->on('permissions')->cascadeOnDelete();
        });

        Schema::create('rbac_migration_audits', function (Blueprint $table) {
            $table->id();
            $table->string('source');
            $table->longText('payload');
            $table->timestamp('created_at')->useCurrent();
        });

        $snapshot = [];
        foreach (['grupo', 'formularios', 'permiso_forms', 'detalle_forms', 'usuario_permisos', 'permiso', 'grupo_permiso'] as $table) {
            if (Schema::hasTable($table)) {
                $query = DB::table($table);
                if (Schema::hasColumn($table, 'id')) {
                    $query->orderBy('id');
                }
                $snapshot[$table] = $query->get()->map(fn ($row) => (array) $row)->all();
            }
        }
        DB::table('rbac_migration_audits')->insert([
            'source' => 'legacy-rbac-before-migration',
            'payload' => json_encode($snapshot, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        ]);

        $usedSlugs = [];
        foreach (DB::table('grupo')->orderBy('id')->get() as $group) {
            $base = Str::slug($group->nombre) ?: 'grupo-'.$group->id;
            $slug = $base;
            $suffix = 2;
            while (in_array($slug, $usedSlugs, true)) {
                $slug = $base.'-'.$suffix++;
            }
            $usedSlugs[] = $slug;
            DB::table('grupo')->where('id', $group->id)->update([
                'slug' => $slug,
                'is_super_admin' => $group->id == 1 || Str::lower($group->nombre) === 'administrador',
            ]);
        }

        $now = now();
        foreach (config('rbac.permissions', []) as $key => $permission) {
            DB::table('permissions')->insert([
                'key' => $key,
                'name' => $permission['name'],
                'module' => $permission['module'],
                'description' => $permission['description'] ?? null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $legacyAssignments = Schema::hasTable('detalle_forms') && Schema::hasTable('permiso_forms')
            ? DB::table('detalle_forms')
                ->join('permiso_forms', 'permiso_forms.id', '=', 'detalle_forms.id_permiso_form')
                ->select('permiso_forms.id_grupo', 'detalle_forms.id_formulario')->distinct()->get()
            : collect();

        $permissionIds = DB::table('permissions')->pluck('id', 'key');
        foreach ($legacyAssignments as $assignment) {
            foreach (config('rbac.permissions', []) as $key => $permission) {
                if ((int) $permission['legacy_form_id'] === (int) $assignment->id_formulario) {
                    DB::table('group_permission')->insertOrIgnore([
                        'id_grupo' => $assignment->id_grupo,
                        'permission_id' => $permissionIds[$key],
                    ]);
                }
            }
        }
    }

    public function down()
    {
        Schema::dropIfExists('group_permission');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('rbac_migration_audits');
        Schema::table('grupo', function (Blueprint $table) {
            $table->dropUnique('grupo_slug_unique');
            $table->dropColumn(['slug', 'is_super_admin']);
        });
    }
}
