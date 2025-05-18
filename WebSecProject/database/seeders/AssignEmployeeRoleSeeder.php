<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AssignEmployeeRoleSeeder extends Seeder
{
    public function run()
    {
        // Create employee role if it doesn't exist
        $employeeRole = Role::firstOrCreate(['name' => 'employee']);

        // Get the current user
        $user = User::where('email', 'your-email@example.com')->first(); // Replace with your email

        if ($user) {
            // Attach the employee role to the user
            $user->roles()->syncWithoutDetaching([$employeeRole->id]);
            $this->command->info('Employee role assigned successfully to user: ' . $user->email);
        } else {
            $this->command->error('User not found');
        }
    }
} 