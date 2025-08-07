<?php

namespace App\Livewire\Role;

use App\Services\RoleService;
use Livewire\Component;
use Spatie\Permission\Models\Permission;

class CreateRole extends Component
{
    public $name, $permissions = [];

    protected $rules = [
        'name' => 'required|string|max:255|unique:roles,name',
        'permissions' => 'array',
    ];

    public function render()
    {
        $allPermissions = Permission::all();
        return view('livewire.role.create-role', ['allPermissions' => $allPermissions]);
    }

    public function create(RoleService $roleService)
    {
        $this->validate();
        try {
            $roleService->createRole([
                'name' => $this->name,
                'permissions' => $this->permissions,
            ]);
            session()->flash('message', 'Role created successfully.');
            $this->reset();
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to create role: ' . $e->getMessage());
        }
    }
}