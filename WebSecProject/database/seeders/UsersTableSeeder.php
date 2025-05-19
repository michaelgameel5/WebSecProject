<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Michael',
                'email' => 'michael@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('Test@123'), 
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
                'google_id' => null,
                'google_token' => null,
                'google_refresh_token' => null,
                'credit' => 0.00,
            ],
            [
                'name' => 'Demiana',
                'email' => 'dodo@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('Dodo@123'), 
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
                'google_id' => null,
                'google_token' => null,
                'google_refresh_token' => null,
                'credit' => 0.00,
            ],
            [
                'name' => 'Mina',
                'email' => 'mina@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('Mina@123'), 
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
                'google_id' => null,
                'google_token' => null,
                'google_refresh_token' => null,
                'credit' => 16706.00,
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
        
        // Assign roles to users
        $michaelUser = User::where('email', 'michael@gmail.com')->first();
        if ($michaelUser) {
            $michaelUser->assignRole('admin');
        }
        
        $demianaUser = User::where('email', 'dodo@gmail.com')->first();
        if ($demianaUser) {
            $demianaUser->assignRole('employee');
        }
        
        $minaUser = User::where('email', 'mina@gmail.com')->first();
        if ($minaUser) {
            $minaUser->assignRole('customer');
        }
    }
}