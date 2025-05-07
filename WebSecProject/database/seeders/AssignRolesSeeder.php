<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class AssignRolesSeeder extends Seeder
{
    public function run()
    {
        // Create roles if they don't exist
        $employeeRole = Role::firstOrCreate(['name' => 'employee']);
        $customerRole = Role::firstOrCreate(['name' => 'customer']);

        // Clear existing role assignments
        DB::table('user_roles')->truncate();

        // Assign customer role to ID 6
        $user = User::find(6);
        if ($user) {
            $user->roles()->attach($customerRole->id);
            $this->command->info('Assigned customer role to ID 6');
        } else {
            $this->command->error('User with ID 6 not found');
        }

        // Assign employee role to ID 24
        $employee = User::find(24);
        if ($employee) {
            $employee->roles()->attach($employeeRole->id);
            $this->command->info('Assigned employee role to ID 24');
        } else {
            $this->command->error('User with ID 24 not found');
        }

        // Assign customer role to all other users
        $otherUsers = User::whereNotIn('id', [6, 24])->get();
        foreach ($otherUsers as $user) {
            $user->roles()->attach($customerRole->id);
        }
        $this->command->info('Assigned customer role to all other users');
    }
} 