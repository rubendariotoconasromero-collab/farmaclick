<?php

namespace Tests\Feature;

use App\Models\Grupo;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserAccessModulesTest extends TestCase
{
    use DatabaseTransactions;

    public function test_role_manager_can_list_groups_and_their_members(): void
    {
        [$manager, $role] = $this->userWithPermission('users.roles.manage');

        $this->actingAs($manager)->getJson('/grupo_listar')->assertOk();
        $this->actingAs($manager)->getJson('/usuario/grupo_usuario?id='.$role->id)
            ->assertOk()
            ->assertJsonFragment(['id' => $manager->id]);
    }

    public function test_user_manager_can_read_effective_role_permissions_but_cannot_administer_catalog(): void
    {
        [$manager, $role] = $this->userWithPermission('users.manage');

        $this->actingAs($manager)->getJson('/rbac/roles/'.$role->id)
            ->assertOk()
            ->assertJsonPath('role.id', $role->id);
        $this->actingAs($manager)->getJson('/rbac/permissions')->assertForbidden();
    }

    public function test_user_can_be_edited_without_changing_its_password(): void
    {
        [$manager, $role] = $this->userWithPermission('users.manage');
        $target = $this->createUser($role, 'usuario-objetivo-'.uniqid(), 'ClaveOriginal123');
        $originalHash = $target->password;

        $this->actingAs($manager)->putJson('/usuario/modificar', [
            'id' => $target->id,
            'nombre' => $target->name,
            'matricula' => $target->matricula,
            'email' => 'actualizado-'.uniqid().'@example.test',
            'password' => '',
            'id_grupo' => $role->id,
            'id_personal' => $target->id_personal,
            'estado' => 1,
        ])->assertOk();

        $target->refresh();
        $this->assertSame($originalHash, $target->password);
        $this->assertTrue(Hash::check('ClaveOriginal123', $target->password));
    }

    public function test_superadministrator_role_is_not_assignable_from_user_management(): void
    {
        [$manager] = $this->userWithPermission('users.manage');
        $superRole = Grupo::where('is_super_admin', true)->firstOrFail();

        $this->actingAs($manager)->getJson('/grupo/selectGrupo')
            ->assertOk()
            ->assertJsonMissing(['id' => $superRole->id]);

        $this->actingAs($manager)->postJson('/usuario/guardar', [
            'nombre' => 'intento-superadmin-'.uniqid(),
            'email' => 'intento-'.uniqid().'@example.test',
            'password' => 'ClaveSegura123',
            'id_grupo' => $superRole->id,
            'id_personal' => DB::table('personal')->value('id'),
            'estado' => 1,
        ])->assertStatus(422);

        $this->actingAs($manager)->getJson('/rbac/roles/'.$superRole->id)->assertForbidden();
    }

    private function userWithPermission(string $permissionKey): array
    {
        $role = Grupo::create([
            'nombre' => 'Rol delegado '.uniqid(),
            'slug' => 'rol-delegado-'.uniqid(),
            'descripcion' => 'Rol temporal de prueba.',
            'estado' => 1,
            'is_super_admin' => false,
        ]);
        $role->permissions()->sync([Permission::where('key', $permissionKey)->firstOrFail()->id]);

        return [$this->createUser($role, 'gestor-'.uniqid(), 'ClaveGestor123'), $role];
    }

    private function createUser(Grupo $role, string $name, string $password): User
    {
        $personalId = DB::table('personal')->value('id');
        $this->assertNotNull($personalId, 'Se necesita al menos un registro de personal para probar usuarios.');

        return User::create([
            'name' => $name,
            'matricula' => 'TEST-'.uniqid(),
            'email' => $name.'@example.test',
            'password' => Hash::make($password),
            'estado' => 1,
            'id_grupo' => $role->id,
            'id_personal' => $personalId,
        ]);
    }
}
