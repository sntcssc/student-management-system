<?php

namespace App\Services;

use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Log;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAllUsers($filters = [])
    {
        return $this->userRepository->all($filters);
    }

    public function createUser(array $data)
    {
        try {
            $user = $this->userRepository->create($data);
            Log::info('User created', ['user_id' => $user->id, 'email' => $user->email]);
            ActivityLog::create([
                'user_id' => auth::id(),
                'action' => 'create_user',
                'description' => "Created user: {$user->email}",
            ]);
            return $user;
        } catch (\Exception $e) {
            Log::error('User creation failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function updateUser($id, array $data)
    {
        try {
            $user = $this->userRepository->update($id, $data);
            Log::info('User updated', ['user_id' => $user->id, 'email' => $user->email]);
            ActivityLog::create([
                'user_id' => auth::id(),
                'action' => 'update_user',
                'description' => "Updated user: {$user->email}",
            ]);
            return $user;
        } catch (\Exception $e) {
            Log::error('User update failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
    public function deleteUser($id)
    {
        try {
            $this->userRepository->delete($id);
            Log::info('User deleted', ['user_id' => $id]);
            ActivityLog::create([
                'user_id' => auth::id(),
                'action' => 'delete_user',
                'description' => "Deleted user with ID: {$id}",
            ]);
        } catch (\Exception $e) {
            Log::error('User deletion failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function restoreUser($id)
    {
        try {
            $user = $this->userRepository->restore($id);
            Log::info('User restored', ['user_id' => $id]);
            ActivityLog::create([
                'user_id' => auth::id(),
                'action' => 'restore_user',
                'description' => "Restored user with ID: {$id}",
            ]);
            return $user;
        } catch (\Exception $e) {
            Log::error('User restoration failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
    
    public function find($id)
    {
        return $this->userRepository->find($id);
    }

    public function findUser($id)
    {
        return $this->userRepository->find($id);
    }

    public function getUserDetails($id)
    {
        $user = $this->findUser($id);
        return $user->load('userDetails');
    }

    public function getUserRoles($id)
    {
        $user = $this->findUser($id);
        return $user->roles;
    }
    public function assignRolesToUser($id, array $roles)
    {
        $user = $this->findUser($id);
        $user->assignRole($roles);
        Log::info('Roles assigned to user', ['user_id' => $id, 'roles' => $roles]);
        ActivityLog::create([
            'user_id' => auth::id(),
            'action' => 'assign_roles',
            'description' => "Assigned roles to user: {$id}",
        ]);
        return $user;
    }
    public function syncRolesToUser($id, array $roles)
    {
        $user = $this->findUser($id);
        $user->syncRoles($roles);
        Log::info('Roles synced to user', ['user_id' => $id, 'roles' => $roles]);
        ActivityLog::create([
            'user_id' => auth::id(),
            'action' => 'sync_roles',
            'description' => "Synced roles to user: {$id}",
        ]);
        return $user;
    }
    public function getUserActivityLogs($id)
    {
        $user = $this->findUser($id);
        return $user->activityLogs()->paginate(10);
    }
    public function logUserActivity($action, $description)
    {
        ActivityLog::create([
            'user_id' => auth::id(),
            'action' => $action,
            'description' => $description,
        ]);
        Log::info('User activity logged', ['action' => $action, 'description' => $description]);
    }
}