<?php

namespace App\Http\Middleware;

use Closure;

class AuthorizeRbacRequest
{
    public function handle($request, Closure $next)
    {
        foreach (config('rbac.route_permissions', []) as $rule) {
            if (isset($rule['methods']) && !in_array($request->method(), $rule['methods'], true)) {
                continue;
            }
            if (!collect($rule['patterns'])->contains(fn ($pattern) => $request->is($pattern))) {
                continue;
            }
            if (!empty($rule['allow_authenticated'])) {
                break;
            }
            if (!collect($rule['any'])->contains(fn ($permission) => $request->user()->hasPermissionTo($permission))) {
                abort(403, 'No tiene permisos para acceder a este recurso.');
            }
            break;
        }
        return $next($request);
    }
}
