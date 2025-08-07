<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserRepository implements UserRepositoryInterface
{

    public function all($filters = [])
    {
        $cacheKey = 'users_' . md5(json_encode($filters));
        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($filters) {
            $query = User::query();
            if (isset($filters['search'])) {
                $query->where('first_name', 'like', '%' . $filters['search'] . '%')
                      ->orWhere('last_name', 'like', '%' . $filters['search'] . '%')
                      ->orWhere('email', 'like', '%' . $filters['search'] . '%');
            }
            if (isset($filters['sort'])) {
                $query->orderBy($filters['sort'], $filters['direction'] ?? 'asc');
            }
            return $query->with('roles')->paginate(10);
        });
    }

    public function find($id)
    {
        return User::with('roles', 'userDetails')->findOrFail($id);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $user = User::create($data);
            if (isset($data['roles'])) {
                $user->assignRole($data['roles']);
            }
            return $user;
        });
    }

    public function update($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $user = $this->find($id);
            $user->update($data);
            if (isset($data['roles'])) {
                $user->syncRoles($data['roles']);
            }
            return $user;
        });
    }

    public function delete($id)
    {
        $user = $this->find($id);
        return $user->delete();
    }

    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        return $user->restore();
    }
}