<?php

namespace App\Livewire\Profile;

use App\Services\ProfileService;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class EditProfile extends Component
{
    use WithFileUploads;

    public $first_name, $last_name, $email, $mobile, $whatsapp, $category, $photo, $bio, $address;

    public function mount()
    {
        $user = auth::user();
        $this->first_name = $user->first_name;
        $this->last_name = $user->last_name;
        $this->email = $user->email;
        $this->mobile = $user->mobile;
        $this->whatsapp = $user->whatsapp;
        $this->category = $user->category;
        $this->bio = $user->userDetails?->bio;
        $this->address = $user->userDetails?->address;
    }

    protected $rules = [
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,{{auth::id()}}',
        'mobile' => 'nullable|string|max:15',
        'whatsapp' => 'nullable|string|max:15',
        'category' => 'nullable|in:Unreserved,SC,ST,OBC',
        'photo' => 'nullable|image|max:2048',
        'bio' => 'nullable|string',
        'address' => 'nullable|string',
    ];

    public function update(ProfileService $profileService)
    {
        $this->validate();
        try {
            $data = [
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'mobile' => $this->mobile,
                'whatsapp' => $this->whatsapp,
                'category' => $this->category,
                'bio' => $this->bio,
                'address' => $this->address,
            ];
            if ($this->photo) {
                $data['photo'] = $this->photo->store('profile_photos', 'public');
            }
            $profileService->updateProfile(auth::id(), $data);
            session()->flash('message', 'Profile updated successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update profile: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.profile.edit-profile');
    }
}