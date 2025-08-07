<?php

namespace App\Livewire\User;

use App\Services\UserService;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class CreateUser extends Component
{
    public $first_name, $last_name, $email, $password, $mobile, $whatsapp, $category, $roles = [];

    protected $rules = [
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8',
        'mobile' => 'nullable|string|max:15',
        'whatsapp' => 'nullable|string|max:15',
        'category' => 'nullable|in:Unreserved,SC,ST,OBC',
        'roles' => 'array',
    ];

    public function render()
    {
        $allRoles = Role::all();
        return view('livewire.user.create-user', ['allRoles' => $allRoles]);
    }

    public function create(UserService $userService)
    {
        $this->validate();
        try {
            $userService->createUser([
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'password' => bcrypt($this->password),
                'mobile' => $this->mobile,
                'whatsapp' => $this->whatsapp,
                'category' => $this->category,
                'roles' => $this->roles,
            ]);
            session()->flash('message', 'User created successfully.');
            $this->reset();
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to create user: ' . $e->getMessage());
        }
    }
}