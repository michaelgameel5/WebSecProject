<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class AssignUserRoles extends Command
{
    protected $signature = 'users:assign-roles';
    protected $description = 'Assign roles to specific users';

    public function handle()
    {
        try {
            // Get or create roles
            $userRole = Role::firstOrCreate(['name' => 'user']);
            $employeeRole = Role::firstOrCreate(['name' => 'employee']);
            $customerRole = Role::firstOrCreate(['name' => 'customer']);

            // Assign roles to User ID 6 (customer)
            $user = User::find(6);
            if ($user) {
                $user->roles()->sync([$userRole->id, $customerRole->id]);
                $this->info('Assigned user and customer roles to ID 6');
            } else {
                $this->error('User with ID 6 not found');
            }

            // Assign roles to User ID 24 (employee)
            $employee = User::find(24);
            if ($employee) {
                $employee->roles()->sync([$userRole->id, $employeeRole->id]);
                $this->info('Assigned user and employee roles to ID 24');
            } else {
                $this->error('User with ID 24 not found');
            }
        } catch (\Exception $e) {
            $this->error('Error assigning roles: ' . $e->getMessage());
        }
    }
} 