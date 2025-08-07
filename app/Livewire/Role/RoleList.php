<?php

namespace App\Livewire\Role;

use App\Services\RoleService;
use Livewire\Component;
use Livewire\WithPagination;

class RoleList extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';

    protected $queryString = ['search', 'sortField', 'sortDirection'];

    public function render(RoleService $roleService)
    {
        $roles = $roleService->getAllRoles([
            'search' => $this->search,
            'sort' => $this->sortField,
            'direction' => $this->sortDirection,
        ]);

        return view('livewire.role.role-list', ['roles' => $roles]);
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

    public function delete($id, RoleService $roleService)
    {
        try {
            $roleService->deleteRole($id);
            session()->flash('message', 'Role deleted successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete role: ' . $e->getMessage());
        }
    }
}