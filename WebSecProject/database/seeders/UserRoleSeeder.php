<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserRoleSeeder extends Seeder
{
    public function run()
    {
        // Assign user role to ID 6
        $user = User::find(6);
        if ($user) {
            DB::table('user_roles')->insert([
                'user_id' => 6,
                'role_id' => 1, // Assuming 1 is the ID for 'user' role
                'created_at' => now(),
                'updated_at' => now()
            ]);
            $this->command->info('Assigned user role to ID 6');
        } else {
            $this->command->error('User with ID 6 not found');
        }

        // Assign employee role to ID 24
        $employee = User::find(24);
        if ($employee) {
            DB::table('user_roles')->insert([
                'user_id' => 24,
                'role_id' => 2, // Assuming 2 is the ID for 'employee' role
                'created_at' => now(),
                'updated_at' => now()
            ]);
            $this->command->info('Assigned employee role to ID 24');
        } else {
            $this->command->error('User with ID 24 not found');
        }
    }
} 