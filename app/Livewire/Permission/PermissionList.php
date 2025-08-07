<?php

namespace App\Livewire\Permission;

use App\Services\PermissionService;
use Livewire\Component;
use Livewire\WithPagination;

class PermissionList extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';

    protected $queryString = ['search', 'sortField', 'sortDirection'];

    public function render(PermissionService $permissionService)
    {
        $permissions = $permissionService->getAllPermissions([
            'search' => $this->search,
            'sort' => $this->sortField,
            'direction' => $this->sortDirection,
        ]);

        return view('livewire.permission.permission-list', ['permissions' => $permissions]);
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function delete($id, PermissionService $permissionService)
    {
        try {
            $permissionService->deletePermission($id);
            session()->flash('message', 'Permission deleted successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete permission: ' . $e->getMessage());
        }
    }
}