<?php

namespace App\Livewire\User;

use App\Services\UserService;
use Livewire\Component;
use Livewire\WithPagination;

class UserList extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'first_name';
    public $sortDirection = 'asc';

    protected $queryString = ['search', 'sortField', 'sortDirection'];

    public function render(UserService $userService)
    {
        $users = $userService->getAllUsers([
            'search' => $this->search,
            'sort' => $this->sortField,
            'direction' => $this->sortDirection,
        ]);

        return view('livewire.user.user-list', ['users' => $users]);
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
}