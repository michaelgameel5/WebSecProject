<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckRolesTable extends Command
{
    protected $signature = 'roles:table';
    protected $description = 'Check the roles table contents';

    public function handle()
    {
        $this->info('Roles in the database:');
        $roles = DB::table('roles')->get();
        foreach ($roles as $role) {
            $this->line("ID: {$role->id}, Name: {$role->name}");
        }

        $this->info("\nRole assignments:");
        $assignments = DB::table('user_roles')
            ->join('roles', 'user_roles.role_id', '=', 'roles.id')
            ->join('users', 'user_roles.user_id', '=', 'users.id')
            ->select('users.id as user_id', 'users.name as user_name', 'roles.name as role_name')
            ->get();

        foreach ($assignments as $assignment) {
            $this->line("User {$assignment->user_id} ({$assignment->user_name}): {$assignment->role_name}");
        }
    }
} 