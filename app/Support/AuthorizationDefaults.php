<?php

namespace App\Support;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AuthorizationDefaults
{
    public static function ensure(): void
    {
        foreach (self::roles() as $roleName) {
            Role::findOrCreate($roleName, 'web');
        }

        foreach (self::permissions() as $permissionName) {
            Permission::findOrCreate($permissionName, 'web');
        }

        Role::findByName('Super Admin', 'web')->syncPermissions(Permission::all());
        Role::findByName('Company Admin', 'web')->syncPermissions([
            'projects.view',
            'projects.manage',
            'tasks.view',
            'tasks.manage',
            'users.manage',
            'subscription.manage',
            'audit.view',
        ]);
        Role::findByName('Manager', 'web')->syncPermissions([
            'projects.view',
            'projects.manage',
            'tasks.view',
            'tasks.manage',
            'audit.view',
        ]);
        Role::findByName('Employee', 'web')->syncPermissions([
            'projects.view',
            'tasks.view',
        ]);
    }

    public static function roles(): array
    {
        return [
            'Super Admin',
            'Company Admin',
            'Manager',
            'Employee',
        ];
    }

    public static function permissions(): array
    {
        return [
            'projects.view',
            'projects.manage',
            'tasks.view',
            'tasks.manage',
            'users.manage',
            'subscription.manage',
            'audit.view',
        ];
    }
}
