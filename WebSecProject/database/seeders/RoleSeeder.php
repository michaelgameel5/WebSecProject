<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Create roles
        $employeeRole = Role::create(['name' => 'employee']);
        $customerRole = Role::create(['name' => 'customer']);
        $adminRole = Role::create(['name' => 'admin']);

        // Create permissions
        $permissions = [
            'manage products',
            'manage vouchers',
            'manage users',
            'manage credit requests',
            'view products',
            'view vouchers',
            'view purchase history',
            'manage cards',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Assign permissions to roles
        $employeeRole->givePermissionTo([
            'manage products',
            'manage vouchers',
            'manage credit requests',
            'view products',
            'view vouchers',
            'view purchase history',
        ]);

        $adminRole->givePermissionTo($permissions);

        $customerRole->givePermissionTo([
            'view products',
            'view vouchers',
            'view purchase history',
            'manage cards',
        ]);
    }
} 