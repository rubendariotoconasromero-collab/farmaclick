<?php

namespace Tests\Feature;

use App\Models\Grupo;
use App\Models\Permission;
use App\Models\User;
use App\Http\Middleware\EnsurePermission;
use App\Support\Navigation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class RbacSystemTest extends TestCase
{
    use DatabaseTransactions;

    public function test_superadministrator_has_every_catalog_permission_and_full_navigation(): void
    {
        $admin = User::whereHas('group', fn ($query) => $query->where('is_super_admin', true))->firstOrFail();

        $this->assertCount(count(config('rbac.permissions')), $admin->permissionKeys());
        $this->assertTrue($admin->hasPermissionTo('users.permissions.manage'));
        $this->assertSame(count(config('navigation')), count(Navigation::forUser($admin)));
    }

    public function test_every_legacy_group_assignment_was_mapped_to_native_permissions(): void
    {
        $audit = DB::table('rbac_migration_audits')
            ->where('source', 'legacy-rbac-before-migration')->latest('id')->first();
        $this->assertNotNull($audit, 'No existe el respaldo de la migración heredada.');
        $snapshot = json_decode($audit->payload, true);
        $permissionForms = collect($snapshot['permiso_forms'])->keyBy('id');
        $legacy = collect($snapshot['detalle_forms'])->map(function ($detail) use ($permissionForms) {
            return (object) [
                'id_grupo' => $permissionForms[$detail['id_permiso_form']]['id_grupo'],
                'id_formulario' => $detail['id_formulario'],
            ];
        })->unique(fn ($assignment) => $assignment->id_grupo.'-'.$assignment->id_formulario);

        foreach ($legacy as $assignment) {
            $expectedKeys = collect(config('rbac.permissions'))->filter(
                fn ($permission) => (int) $permission['legacy_form_id'] === (int) $assignment->id_formulario
            )->keys();
            $actualKeys = Grupo::findOrFail($assignment->id_grupo)->permissions()->pluck('key');
            foreach ($expectedKeys as $key) {
                $this->assertTrue($actualKeys->contains($key), "Falta {$key} en el grupo {$assignment->id_grupo}");
            }
        }
    }

    public function test_admin_can_use_protected_rbac_api(): void
    {
        $admin = User::whereHas('group', fn ($query) => $query->where('is_super_admin', true))->firstOrFail();

        $this->actingAs($admin)->getJson('/rbac/permissions')
            ->assertOk()
            ->assertJsonCount(Permission::query()->distinct()->count('module'));
    }

    public function test_superadministrator_menu_renders_after_a_fresh_request(): void
    {
        $admin = User::whereHas('group', fn ($query) => $query->where('is_super_admin', true))->firstOrFail();

        $this->actingAs($admin)->get('/main')
            ->assertOk()
            ->assertSee('Datos gráficos')
            ->assertSee('Permisos')
            ->assertSee('Reportes');
    }

    public function test_non_privileged_user_is_denied_permission_administration(): void
    {
        $user = new User();
        $user->setRelation('group', new Grupo(['is_super_admin' => false]));
        $request = Request::create('/rbac/permissions', 'GET');
        $request->setUserResolver(fn () => $user);

        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);
        (new EnsurePermission())->handle($request, fn () => response()->noContent(), 'users.permissions.manage');
    }

    public function test_inactive_role_has_no_effective_permissions(): void
    {
        $user = new User();
        $user->setRelation('group', new Grupo(['estado' => 0, 'is_super_admin' => false]));

        $this->assertSame([], $user->permissionKeys());
        $this->assertFalse($user->hasPermissionTo('dashboard.view'));
    }

    public function test_role_can_be_created_and_permissions_are_synchronized_atomically(): void
    {
        $admin = User::whereHas('group', fn ($query) => $query->where('is_super_admin', true))->firstOrFail();
        $permissionIds = Permission::whereIn('key', ['dashboard.view', 'inventory.view'])->pluck('id')->all();

        $response = $this->actingAs($admin)->postJson('/rbac/roles', [
            'nombre' => 'Auditor de prueba',
            'slug' => 'auditor-prueba-rbac',
            'descripcion' => 'Rol creado por la prueba automatizada.',
            'estado' => 1,
            'permission_ids' => $permissionIds,
        ])->assertCreated();

        $role = Grupo::findOrFail($response->json('id'));
        $this->assertFalse($role->is_super_admin);
        $this->assertEqualsCanonicalizing($permissionIds, $role->permissions()->pluck('permissions.id')->all());
    }

    public function test_superadministrator_role_cannot_be_downgraded(): void
    {
        $admin = User::whereHas('group', fn ($query) => $query->where('is_super_admin', true))->firstOrFail();

        $this->actingAs($admin)->putJson('/rbac/roles/'.$admin->id_grupo, [
            'nombre' => 'Administrador', 'slug' => 'administrador', 'estado' => 1,
            'permission_ids' => [Permission::firstOrFail()->id],
        ])->assertStatus(422);
    }
}
