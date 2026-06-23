<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Gopal',
            'email' => 'gopal@gmail.com',
            'password' => Hash::make('1234'), // You can change this
            'mobile' => '1234567890',
            'role' => 'admin',
            'status' => 1, // 1 for active
        ]);
    }
}