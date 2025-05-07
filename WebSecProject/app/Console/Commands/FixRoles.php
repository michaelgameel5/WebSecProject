<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class FixRoles extends Command
{
    protected $signature = 'roles:fix';
    protected $description = 'Fix role assignments by removing duplicates and ensuring correct roles';

    public function handle()
    {
        try {
            // Get or create the roles
            $userRole = Role::firstOrCreate(['name' => 'user']);
            $employeeRole = Role::firstOrCreate(['name' => 'employee']);
            $customerRole = Role::firstOrCreate(['name' => 'customer']);

            // Assign roles to User ID 6 (customer)
            $user6 = User::find(6);
            if ($user6) {
                // Use sync to ensure only these roles are assigned
                $user6->roles()->sync([$userRole->id, $customerRole->id]);
                $this->info('Assigned user and customer roles to User ID 6');
            } else {
                $this->error('User ID 6 not found');
            }

            // Assign roles to User ID 24 (employee)
            $user24 = User::find(24);
            if ($user24) {
                // Use sync to ensure only these roles are assigned
                $user24->roles()->sync([$userRole->id, $employeeRole->id]);
                $this->info('Assigned user and employee roles to User ID 24');
            } else {
                $this->error('User ID 24 not found');
            }

            $this->info('Role assignments have been fixed successfully');
        } catch (\Exception $e) {
            $this->error('Error fixing roles: ' . $e->getMessage());
        }
    }
} 