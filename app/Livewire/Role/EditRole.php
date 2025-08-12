<?php

namespace App\Livewire\Role;

use App\Services\RoleService;
use Livewire\Component;
use Spatie\Permission\Models\Permission;

class EditRole extends Component
{
    public $roleId, $name, $permissions = [];

    // protected $rules = [
    //     'name' => 'required|string|max:255|unique:roles,name,{{roleId}}',
    //     'permissions' => 'array',
    // ];

    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:roles,name,' . $this->roleId,
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name',
        ];
    }

    public function mount($id, RoleService $roleService)
    {
        $role = $roleService->find($id);
        $this->roleId = $role->id;
        $this->name = $role->name;
        $this->permissions = $role->permissions->pluck('name')->toArray();
    }

    public function update(RoleService $roleService)
    {
        $this->validate();
        try {
            $roleService->updateRole($this->roleId, [
                'name' => $this->name,
                'permissions' => $this->permissions,
            ]);
            session()->flash('message', 'Role updated successfully.');
            $this->redirectRoute('roles.index');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update role: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $allPermissions = Permission::all();
        return view('livewire.role.edit-role', ['allPermissions' => $allPermissions]);
    }
}