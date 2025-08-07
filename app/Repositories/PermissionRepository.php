<?php

namespace App\Repositories;

use Spatie\Permission\Models\Permission;
use App\Repositories\Interfaces\PermissionRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class PermissionRepository implements PermissionRepositoryInterface
{
    public function all($filters = [])
    {
        $cacheKey = 'permissions_' . md5(json_encode($filters));
        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($filters) {
            $query = Permission::query();
            if (isset($filters['search'])) {
                $query->where('name', 'like', '%' . $filters['search'] . '%');
            }
            if (isset($filters['sort'])) {
                $query->orderBy($filters['sort'], $filters['direction'] ?? 'asc');
            }
            return $query->paginate(10);
        });
    }

    public function find($id)
    {
        return Permission::findOrFail($id);
    }

    public function create(array $data)
    {
        return Permission::create(['name' => $data['name']]);
    }

    public function update($id, array $data)
    {
        $permission = $this->find($id);
        $permission->update(['name' => $data['name']]);
        return $permission;
    }

    public function delete($id)
    {
        $permission = $this->find($id);
        return $permission->delete();
    }
}