<?php

namespace App\Http\Controllers;

use App\Services\ProfileService;
use App\Http\Requests\Profile\UpdateProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    protected $profileService;

    public function __construct(ProfileService $profileService)
    {
        // $this->middleware('auth');
        $this->profileService = $profileService;
    }

    public function edit()
    {
        $user = auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(UpdateProfileRequest $request)
    {
        $this->profileService->updateProfile(auth::id(), $request->validated());
        return redirect()->route('profile.edit')->with('message', 'Profile updated successfully.');
    }
}