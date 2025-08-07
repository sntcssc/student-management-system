<?php

namespace App\Services;

use App\Repositories\Interfaces\RoleRepositoryInterface;
use Illuminate\Support\Facades\Log;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class RoleService
{
    protected $roleRepository;

    public function __construct(RoleRepositoryInterface $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function getAllRoles($filters = [])
    {
        return $this->roleRepository->all($filters);
    }

    public function find($id)
    {
        return $this->roleRepository->find($id);
    }

    public function createRole(array $data)
    {
        try {
            $role = $this->roleRepository->create($data);
            Log::info('Role created', ['role_id' => $role->id, 'name' => $role->name]);
            ActivityLog::create([
                'user_id' => auth::id(),
                'action' => 'create_role',
                'description' => "Created role: {$role->name}",
            ]);
            return $role;
        } catch (\Exception $e) {
            Log::error('Role creation failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function updateRole($id, array $data)
    {
        try {
            $role = $this->roleRepository->update($id, $data);
            Log::info('Role updated', ['role_id' => $role->id, 'name' => $role->name]);
            ActivityLog::create([
                'user_id' => auth::id(),
                'action' => 'update_role',
                'description' => "Updated role: {$role->name}",
            ]);
            return $role;
        } catch (\Exception $e) {
            Log::error('Role update failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function deleteRole($id)
    {
        try {
            $role = $this->roleRepository->delete($id);
            Log::info('Role deleted', ['role_id' => $id]);
            ActivityLog::create([
                'user_id' => auth::id(),
                'action' => 'delete_role',
                'description' => "Deleted role: {$id}",
            ]);
            return $role;
        } catch (\Exception $e) {
            Log::error('Role deletion failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}