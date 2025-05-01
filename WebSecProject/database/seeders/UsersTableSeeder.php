<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'), // Hash the password
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
                'google_id' => null,
                'google_token' => null,
                'google_refresh_token' => null,
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane.smith@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
                'google_id' => '1234567890',
                'google_token' => 'abcde12345',
                'google_refresh_token' => 'fghij67890',
            ],
            [
                'name' => 'Alice Johnson',
                'email' => 'alice.johnson@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
                'google_id' => null,
                'google_token' => null,
                'google_refresh_token' => null,
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}