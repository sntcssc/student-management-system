@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-4">
        <h2 class="text-xl font-bold mb-4">Edit Profile</h2>
        <form action="{{ route('profile.update', $user->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label for="first_name" class="block text-sm font-medium">First Name</label>
                <input type="text" name="first_name" id="first_name" class="w-full p-2 rounded dark:bg-gray-800 dark:text-gray-200" value="{{ old('first_name', $user->first_name) }}">
                @error('first_name') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="last_name" class="block text-sm font-medium">Last Name</label>
                <input type="text" name="last_name" id="last_name" class="w-full p-2 rounded dark:bg-gray-800 dark:text-gray-200" value="{{ old('last_name', $user->last_name) }}">
                @error('last_name') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="email" class="block text-sm font-medium">Email</label>
                <input type="email" name="email" id="email" class="w-full p-2 rounded dark:bg-gray-800 dark:text-gray-200" value="{{ old('email', $user->email) }}">
                @error('email') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="mobile" class="block text-sm font-medium">Mobile</label>
                <input type="text" name="mobile" id="mobile" class="w-full p-2 rounded dark:bg-gray-800 dark:text-gray-200" value="{{ old('mobile', $user->mobile) }}">
                @error('mobile') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="whatsapp" class="block text-sm font-medium">WhatsApp</label>
                <input type="text" name="whatsapp" id="whatsapp" class="w-full p-2 rounded dark:bg-gray-800 dark:text-gray-200" value="{{ old('whatsapp', $user->whatsapp) }}">
                @error('whatsapp') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="category" class="block text-sm font-medium">Category</label>
                <select name="category" id="category" class="w-full p-2 rounded dark:bg-gray-800 dark:text-gray-200">
                    <option value="">Select Category</option>
                    <option value="Unreserved" {{ old('category', $user->category) === 'Unreserved' ? 'selected' : '' }}>Unreserved</option>
                    <option value="SC" {{ old('category', $user->category) === 'SC' ? 'selected' : '' }}>SC</option>
                    <option value="ST" {{ old('category', $user->category) === 'ST' ? 'selected' : '' }}>ST</option>
                    <option value="OBC" {{ old('category', $user->category) === 'OBC' ? 'selected' : '' }}>OBC</option>
                </select>
                @error('category') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="photo" class="block text-sm font-medium">Profile Photo</label>
                <input type="file" name="photo" id="photo" class="w-full p-2 rounded dark:bg-gray-800 dark:text-gray-200">
                @error('photo') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="bio" class="block text-sm font-medium">Bio</label>
                <textarea name="bio" id="bio" class="w-full p-2 rounded dark:bg-gray-800 dark:text-gray-200">{{ old('bio', $user->userDetails?->bio) }}</textarea>
                @error('bio') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="address" class="block text-sm font-medium">Address</label>
                <textarea name="address" id="address" class="w-full p-2 rounded dark:bg-gray-800 dark:text-gray-200">{{ old('address', $user->userDetails?->address) }}</textarea>
                @error('address') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="bg-blue-500 text-white p-2 rounded">Update Profile</button>
        </form>
    </div>
@endsection