<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Remove old permissions not in the new list
        $newPermissions = [
            'manage_users',
            'add_products',
            'edit_products',
            'delete_products',
            'manage_customers',
            'change_passwords',
        ];
        \Spatie\Permission\Models\Permission::whereNotIn('name', $newPermissions)->delete();

        // Remove old roles not in the new list (optional, comment out if you want to keep old roles)
        $newRoles = ['customer', 'employee', 'admin', 'support agent', 'manager'];
        \Spatie\Permission\Models\Role::whereNotIn('name', $newRoles)->delete();

        // Create permissions
        $permissions = $newPermissions;

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles
        $customer = Role::firstOrCreate(['name' => 'customer']);
        $employee = Role::firstOrCreate(['name' => 'employee']);
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $supportAgent = Role::firstOrCreate(['name' => 'support agent']);
        $manager = Role::firstOrCreate(['name' => 'manager']);

        // Assign permissions to roles
        // Admins: all permissions (manage all users, add employees, etc.)
        $admin->givePermissionTo($permissions);
        // Employees: manage customers only
        $employee->syncPermissions(['manage_customers']);
        // Support agents: manage all users and change their passwords (manage_users, manage_customers, change_passwords)
        $supportAgent->syncPermissions(['manage_users', 'manage_customers', 'change_passwords']);
        // Customers: only change their own password
        $customer->syncPermissions(['change_passwords']);
        $manager->syncPermissions([
            'manage_users',
            'add_products',
            'edit_products',
            'delete_products',
            'manage_customers',
        ]);

        // Example: Assign roles to users (uncomment and adjust as needed)
        // $user = User::where('email', 'customer@example.com')->first();
        // if ($user) {
        //     $user->assignRole('customer');
        // }
        // $employeeUser = User::where('email', 'employee@example.com')->first();
        // if ($employeeUser) {
        //     $employeeUser->assignRole('employee');
        // }
        // $adminUser = User::where('email', 'admin@example.com')->first();
        // if ($adminUser) {
        //     $adminUser->assignRole('admin');
        // }
    }
} 