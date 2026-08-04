<?php

namespace App\Support;

use App\Models\User;

class Navigation
{
    public static function forUser(User $user): array
    {
        return collect(config('navigation', []))->map(function ($item) use ($user) {
            if (isset($item['children'])) {
                $item['children'] = collect($item['children'])->filter(
                    fn ($child) => $user->hasPermissionTo($child['permission'])
                )->values()->all();
                return $item['children'] ? $item : null;
            }
            return $user->hasPermissionTo($item['permission']) ? $item : null;
        })->filter()->values()->all();
    }

    public static function routePermissions(): array
    {
        $permissions = ['inicio' => 'dashboard.view'];
        foreach (config('navigation', []) as $item) {
            foreach ($item['children'] ?? [$item] as $link) {
                $permissions[$link['route']] = $link['permission'];
            }
        }
        return $permissions;
    }
}
