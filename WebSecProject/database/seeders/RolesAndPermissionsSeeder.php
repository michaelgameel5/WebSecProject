<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Product permissions
            'view products',
            'create products',
            'edit products',
            'delete products',
            
            // Card permissions
            'view cards',
            'create cards',
            'deactivate cards',
            'request credit',
            'approve credit',
            'reject credit',
            
            // Voucher permissions
            'view vouchers',
            'create vouchers',
            'delete vouchers',
            
            // User management permissions
            'view users',
            'edit users',
            'delete users',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        $roles = [
            'admin' => $permissions,
            'employee' => [
                'view products',
                'create products',
                'edit products',
                'view cards',
                'approve credit',
                'reject credit',
                'view vouchers',
                'create vouchers',
                'view users',
            ],
            'customer' => [
                'view products',
                'view cards',
                'create cards',
                'deactivate cards',
                'request credit',
            ],
        ];

        foreach ($roles as $role => $rolePermissions) {
            $role = Role::create(['name' => $role]);
            $role->givePermissionTo($rolePermissions);
        }
    }
} 