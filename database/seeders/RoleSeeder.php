<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */ 
    public function run(): void
    {
        // Create Roles
        $roles = config('roles.roles');
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // Create Permissions
        $permissions = config('roles.permissions');
        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName]);
        }

        // Assign Permissions to Roles
        $rolePermissions = config('roles.role_permissions');
        foreach ($rolePermissions as $roleName => $assignedPermissions) {
            $role = Role::findByName($roleName);
            if ($assignedPermissions[0] === '*') {
                $role->givePermissionTo(Permission::all());
            } else {
                $role->syncPermissions($assignedPermissions);
            }
        }
    }
}
