<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::create(['name' => 'admin']);
        $admin->syncPermissions([
            'create-user', 'edit-user', 'view-user', 'delete-user',
            'create-role', 'edit-role', 'view-role', 'delete-role',
            'create-permission', 'edit-permission', 'view-permission', 'delete-permission',
        ]);

        $teacher = Role::create(['name' => 'teacher']);
        $teacher->syncPermissions(['view-user']);

        $finance = Role::create(['name' => 'finance']);
        $finance->syncPermissions(['manage-transactions', 'view-transactions']);
    }
}
