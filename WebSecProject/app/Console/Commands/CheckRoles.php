<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Role;

class CheckRoles extends Command
{
    protected $signature = 'roles:check';
    protected $description = 'Check roles in the database';

    public function handle()
    {
        $this->info('Checking roles in the database...');

        // Check roles table
        $roles = Role::all();
        $this->info("\nRoles in the database:");
        foreach ($roles as $role) {
            $this->info("- {$role->name} (ID: {$role->id})");
        }

        // Check user roles
        $this->info("\nUser role assignments:");
        $users = User::with('roles')->get();
        foreach ($users as $user) {
            $roleNames = $user->roles->pluck('name')->join(', ');
            $this->info("- User {$user->id} ({$user->name}): {$roleNames}");
        }
    }
} 