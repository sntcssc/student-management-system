<?php

namespace Tests\Unit;

use App\Services\PermissionService;
use App\Repositories\Interfaces\PermissionRepositoryInterface;
use Tests\TestCase;
use Mockery;
use Spatie\Permission\Models\Permission;

class PermissionServiceTest extends TestCase
{
    protected $permissionService;
    protected $permissionRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->permissionRepository = Mockery::mock(PermissionRepositoryInterface::class);
        $this->permissionService = new PermissionService($this->permissionRepository);
    }

    public function test_create_permission()
    {
        $data = ['name' => 'view-reports'];
        $permission = new Permission($data);
        $this->permissionRepository->shouldReceive('create')->once()->with($data)->andReturn($permission);

        $result = $this->permissionService->createPermission($data);

        $this->assertEquals($permission->name, $result->name);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}