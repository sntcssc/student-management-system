<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PermissionManagementTest extends TestCase
{
    public function test_admin_can_access_permission_list()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->get(route('permissions.index'));

        $response->assertStatus(200);
    }

    public function test_non_admin_cannot_access_permission_list()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('permissions.index'));

        $response->assertStatus(403);
    }
}