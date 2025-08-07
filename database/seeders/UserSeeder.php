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
        $admin = User::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@admin.com',
            'password' => Hash::make('12345678'),
            'category' => 'Unreserved',
        ]);
        $admin->assignRole('admin');

        $teacher = User::create([
            'first_name' => 'Teacher',
            'last_name' => 'User',
            'email' => 'teacher@example.com',
            'password' => Hash::make('password'),
            'category' => 'OBC',
        ]);
        $teacher->assignRole('teacher');

        $finance = User::create([
            'first_name' => 'Finance',
            'last_name' => 'User',
            'email' => 'finance@example.com',
            'password' => Hash::make('password'),
            'category' => 'SC',
        ]);
        $finance->assignRole('finance');
    }
}
