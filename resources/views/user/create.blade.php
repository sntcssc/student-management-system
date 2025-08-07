@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-4">
        <h2 class="text-xl font-bold mb-4">Create User</h2>
        <form action="{{ route('users.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="first_name" class="block text-sm font-medium">First Name</label>
                <input type="text" name="first_name" id="first_name" class="w-full p-2 rounded dark:bg-gray-800 dark:text-gray-200" value="{{ old('first_name') }}">
                @error('first_name') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="last_name" class="block text-sm font-medium">Last Name</label>
                <input type="text" name="last_name" id="last_name" class="w-full p-2 rounded dark:bg-gray-800 dark:text-gray-200" value="{{ old('last_name') }}">
                @error('last_name') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="email" class="block text-sm font-medium">Email</label>
                <input type="email" name="email" id="email" class="w-full p-2 rounded dark:bg-gray-800 dark:text-gray-200" value="{{ old('email') }}">
                @error('email') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="password" class="block text-sm font-medium">Password</label>
                <input type="password" name="password" id="password" class="w-full p-2 rounded dark:bg-gray-800 dark:text-gray-200">
                @error('password') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="mobile" class="block text-sm font-medium">Mobile</label>
                <input type="text" name="mobile" id="mobile" class="w-full p-2 rounded dark:bg-gray-800 dark:text-gray-200" value="{{ old('mobile') }}">
                @error('mobile') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="whatsapp" class="block text-sm font-medium">WhatsApp</label>
                <input type="text" name="whatsapp" id="whatsapp" class="w-full p-2 rounded dark:bg-gray-800 dark:text-gray-200" value="{{ old('whatsapp') }}">
                @error('whatsapp') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="category" class="block text-sm font-medium">Category</label>
                <select name="category" id="category" class="w-full p-2 rounded dark:bg-gray-800 dark:text-gray-200">
                    <option value="">Select Category</option>
                    <option value="Unreserved" {{ old('category') === 'Unreserved' ? 'selected' : '' }}>Unreserved</option>
                    <option value="SC" {{ old('category') === 'SC' ? 'selected' : '' }}>SC</option>
                    <option value="ST" {{ old('category') === 'ST' ? 'selected' : '' }}>ST</option>
                    <option value="OBC" {{ old('category') === 'OBC' ? 'selected' : '' }}>OBC</option>
                </select>
                @error('category') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium">Roles</label>
                @foreach (\Spatie\Permission\Models\Role::all() as $role)
                    <label>
                        <input type="checkbox" name="roles[]" value="{{ $role->name }}" {{ in_array($role->name, old('roles', [])) ? 'checked' : '' }}> {{ $role->name }}
                    </label>
                @endforeach
                @error('roles') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="bg-blue-500 text-white p-2 rounded">Create User</button>
        </form>
    </div>
@endsection