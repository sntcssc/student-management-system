<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'create-user', 'edit-user', 'view-user', 'delete-user',
            'create-role', 'edit-role', 'view-role', 'delete-role',
            'manage-transactions', 'view-transactions', 
            'create-permission', 'edit-permission', 'view-permission', 'delete-permission',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
