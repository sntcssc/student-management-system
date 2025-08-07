<?php

namespace App\Services;

use App\Repositories\Interfaces\PermissionRepositoryInterface;
use Illuminate\Support\Facades\Log;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class PermissionService
{
    protected $permissionRepository;

    public function __construct(PermissionRepositoryInterface $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    public function getAllPermissions($filters = [])
    {
        return $this->permissionRepository->all($filters);
    }

    public function find($id)
    {
        return $this->permissionRepository->find($id);
    }

    public function createPermission(array $data)
    {
        try {
            $permission = $this->permissionRepository->create($data);
            Log::info('Permission created', ['permission_id' => $permission->id, 'name' => $permission->name]);
            ActivityLog::create([
                'user_id' => auth::id(),
                'action' => 'create_permission',
                'description' => "Created permission: {$permission->name}",
            ]);
            return $permission;
        } catch (\Exception $e) {
            Log::error('Permission creation failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function updatePermission($id, array $data)
    {
        try {
            $permission = $this->permissionRepository->update($id, $data);
            Log::info('Permission updated', ['permission_id' => $id, 'name' => $permission->name]);
            ActivityLog::create([
                'user_id' => auth::id(),
                'action' => 'update_permission',
                'description' => "Updated permission: {$permission->name}",
            ]);
            return $permission;
        } catch (\Exception $e) {
            Log::error('Permission update failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function deletePermission($id)
    {
        try {
            $permission = $this->permissionRepository->delete($id);
            Log::info('Permission deleted', ['permission_id' => $id]);
            ActivityLog::create([
                'user_id' => auth::id(),
                'action' => 'delete_permission',
                'description' => "Deleted permission: {$id}",
            ]);
            return $permission;
        } catch (\Exception $e) {
            Log::error('Permission deletion failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}