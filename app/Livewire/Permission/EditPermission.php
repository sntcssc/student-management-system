<?php

namespace App\Livewire\Permission;

use App\Services\PermissionService;
use Livewire\Component;

class EditPermission extends Component
{
    public $permissionId, $name;

    // protected $rules = [
    //     'name' => 'required|string|max:255|unique:permissions,name,{{permissionId}}',
    // ];

    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:permissions,name,' . $this->permissionId,
        ];
    }


    public function mount($id, PermissionService $permissionService)
    {
        $permission = $permissionService->find($id);
        $this->permissionId = $permission->id;
        $this->name = $permission->name;
    }

    public function update(PermissionService $permissionService)
    {
        $this->validate();
        try {
            $permissionService->updatePermission($this->permissionId, ['name' => $this->name]);
            session()->flash('message', 'Permission updated successfully.');
            $this->redirectRoute('permissions.index');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update permission: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.permission.edit-permission');
    }
}