<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use App\Models\User;

class AssignEmployeeRole extends Command
{
    protected $signature = 'role:assign-employee {email}';
    protected $description = 'Assign the employee role to a user';

    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User with email {$email} not found.");
            return 1;
        }

        $role = Role::firstOrCreate(['name' => 'employee']);
        $user->assignRole($role);

        $this->info("Employee role assigned to {$email} successfully.");
        return 0;
    }
} 