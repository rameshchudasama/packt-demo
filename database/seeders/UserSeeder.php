<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(['email' => 'admin@gmail.com'], [
            'role_id' => 1,
            'name' => 'Test Admin',
            'password' => Hash::make('Admin@123'),
        ]);

        User::firstOrCreate(['email' => 'user@gmail.com'], [
            'role_id' => 2,
            'name' => 'Test User',
            'password' => Hash::make('User@123'),
        ]);
    }
}
