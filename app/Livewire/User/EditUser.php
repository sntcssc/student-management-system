<?php

namespace App\Livewire\User;

use App\Services\UserService;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class EditUser extends Component
{
    public $userId, $first_name, $last_name, $email, $mobile, $whatsapp, $category, $roles = [];

    protected $rules = [
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,{{userId}}',
        'mobile' => 'nullable|string|max:15',
        'whatsapp' => 'nullable|string|max:15',
        'category' => 'nullable|in:Unreserved,SC,ST,OBC',
        'roles' => 'array',
    ];

    public function mount($id, UserService $userService)
    {
        $user = $userService->find($id);
        $this->userId = $user->id;
        $this->first_name = $user->first_name;
        $this->last_name = $user->last_name;
        $this->email = $user->email;
        $this->mobile = $user->mobile;
        $this->whatsapp = $user->whatsapp;
        $this->category = $user->category;
        $this->roles = $user->roles->pluck('name')->toArray();
    }

    public function update(UserService $userService)
    {
        $this->validate();
        try {
            $userService->updateUser($this->userId, [
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'mobile' => $this->mobile,
                'whatsapp' => $this->whatsapp,
                'category' => $this->category,
                'roles' => $this->roles,
            ]);
            session()->flash('message', 'User updated successfully.');
            $this->redirectRoute('users.index');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update user: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $allRoles = Role::all();
        return view('livewire.user.edit-user', ['allRoles' => $allRoles]);
    }
}