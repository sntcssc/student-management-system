<?php

namespace App\Repositories;

use Spatie\Permission\Models\Role;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class RoleRepository implements RoleRepositoryInterface
{
    public function all($filters = [])
    {
        $cacheKey = 'roles_' . md5(json_encode($filters));
        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($filters) {
            $query = Role::query();
            if (isset($filters['search'])) {
                $query->where('name', 'like', '%' . $filters['search'] . '%');
            }
            if (isset($filters['sort'])) {
                $query->orderBy($filters['sort'], $filters['direction'] ?? 'asc');
            }
            return $query->with('permissions')->paginate(10);
        });
    }

    public function find($id)
    {
        return Role::with('permissions')->findOrFail($id);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $role = Role::create(['name' => $data['name']]);
            if (isset($data['permissions'])) {
                $role->syncPermissions($data['permissions']);
            }
            return $role;
        });
    }

    public function update($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $role = $this->find($id);
            $role->update(['name' => $data['name']]);
            if (isset($data['permissions'])) {
                $role->syncPermissions($data['permissions']);
            }
            return $role;
        });
    }

    public function delete($id)
    {
        $role = $this->find($id);
        return $role->delete();
    }
}