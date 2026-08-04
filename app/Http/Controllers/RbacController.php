<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class RbacController extends Controller
{
    public function roles(Request $request)
    {
        return Grupo::query()
            ->withCount(['users', 'permissions'])
            ->when($request->buscar, fn ($query, $search) => $query->where('nombre', 'like', "%{$search}%"))
            ->orderByDesc('is_super_admin')->orderBy('nombre')->paginate(15);
    }

    public function permissions()
    {
        return Permission::query()->orderBy('module')->orderBy('name')->get()->groupBy('module');
    }

    public function show(Request $request, Grupo $role)
    {
        abort_if(
            $role->is_super_admin && !$request->user()->isSuperAdmin(),
            403,
            'El rol superadministrador es reservado.'
        );

        return response()->json([
            'role' => $role->loadCount('users'),
            'permissions' => $role->permissions()->orderBy('module')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateRole($request);
        return DB::transaction(function () use ($data) {
            $role = Grupo::create([
                'nombre' => $data['nombre'], 'slug' => $data['slug'],
                'descripcion' => $data['descripcion'] ?? null, 'estado' => $data['estado'],
                'is_super_admin' => false,
            ]);
            $role->permissions()->sync($data['permission_ids']);
            return response()->json($role, 201);
        });
    }

    public function update(Request $request, Grupo $role)
    {
        abort_if($role->is_super_admin, 422, 'El rol superadministrador no puede perder privilegios.');
        $data = $this->validateRole($request, $role->id);
        DB::transaction(function () use ($role, $data) {
            $role->update([
                'nombre' => $data['nombre'], 'slug' => $data['slug'],
                'descripcion' => $data['descripcion'] ?? null, 'estado' => $data['estado'],
            ]);
            $role->permissions()->sync($data['permission_ids']);
        });
        return response()->json($role->fresh());
    }

    public function updatePermissions(Request $request, Grupo $role)
    {
        abort_if($role->is_super_admin, 422, 'El superadministrador siempre dispone de acceso total.');
        $data = $request->validate(['permission_ids' => ['array'], 'permission_ids.*' => ['integer', 'exists:permissions,id']]);
        $role->permissions()->sync($data['permission_ids'] ?? []);
        return response()->json(['message' => 'Permisos actualizados.', 'permission_ids' => $role->permissions()->pluck('permissions.id')]);
    }

    public function updateStatus(Request $request, Grupo $role)
    {
        abort_if($role->is_super_admin, 422, 'El rol superadministrador debe permanecer activo.');
        $data = $request->validate(['estado' => ['required', Rule::in([0, 1, '0', '1'])]]);
        $role->update(['estado' => $data['estado']]);
        return response()->json(['message' => 'Estado actualizado.']);
    }

    private function validateRole(Request $request, $ignoreId = null): array
    {
        return $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'slug' => ['required', 'alpha_dash', 'max:100', Rule::unique('grupo', 'slug')->ignore($ignoreId)],
            'descripcion' => ['nullable', 'string', 'max:255'],
            'estado' => ['required', Rule::in([0, 1, '0', '1'])],
            'permission_ids' => ['required', 'array', 'min:1'],
            'permission_ids.*' => ['integer', 'exists:permissions,id'],
        ]);
    }
}
