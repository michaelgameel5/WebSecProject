<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SetupRoles extends Command
{
    protected $signature = 'roles:setup';
    protected $description = 'Set up roles and permissions';

    public function handle()
    {
        $this->info('Setting up roles and permissions...');

        // Create roles
        $employeeRole = Role::firstOrCreate(['name' => 'employee']);
        $customerRole = Role::firstOrCreate(['name' => 'customer']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        $this->info('Roles created successfully.');

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
            Permission::firstOrCreate(['name' => $permission]);
        }

        $this->info('Permissions created successfully.');

        // Assign permissions to roles
        $employeeRole->syncPermissions([
            'manage products',
            'manage vouchers',
            'manage credit requests',
            'view products',
            'view vouchers',
            'view purchase history',
        ]);

        $adminRole->syncPermissions($permissions);

        $customerRole->syncPermissions([
            'view products',
            'view vouchers',
            'view purchase history',
            'manage cards',
        ]);

        $this->info('Permissions assigned to roles successfully.');

        return 0;
    }
} 