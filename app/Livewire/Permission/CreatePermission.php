<?php

namespace App\Livewire\Permission;

use App\Services\PermissionService;
use Livewire\Component;

class CreatePermission extends Component
{
    public $name;

    protected $rules = [
        'name' => 'required|string|max:255|unique:permissions,name',
    ];

    public function create(PermissionService $permissionService)
    {
        $this->validate();
        try {
            $permissionService->createPermission(['name' => $this->name]);
            session()->flash('message', 'Permission created successfully.');
            $this->reset();
            $this->redirectRoute('permissions.index');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to create permission: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.permission.create-permission');
    }
}