<?php

namespace App\Http\Middleware;

use Closure;

class EnsurePermission
{
    public function handle($request, Closure $next, ...$permissions)
    {
        $user = $request->user();
        if (!$user || !collect($permissions)->contains(fn ($permission) => $user->hasPermissionTo($permission))) {
            abort(403, 'No tiene permisos para realizar esta acción.');
        }
        return $next($request);
    }
}
