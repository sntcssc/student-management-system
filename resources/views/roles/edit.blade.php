@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-4">
        <h2 class="text-xl font-bold mb-4">Edit Role</h2>
        <form action="{{ route('roles.update', $role->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label for="name" class="block text-sm font-medium">Role Name</label>
                <input type="text" name="name" id="name" class="w-full p-2 rounded dark:bg-gray-800 dark:text-gray-200" value="{{ old('name', $role->name) }}">
                @error('name') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium">Permissions</label>
                @foreach (\Spatie\Permission\Models\Permission::all() as $permission)
                    <label>
                        <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" {{ in_array($permission->name, old('permissions', $role->permissions->pluck('name')->toArray())) ? 'checked' : '' }}> {{ $permission->name }}
                    </label>
                @endforeach
                @error('permissions') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="bg-blue-500 text-white p-2 rounded">Update Role</button>
        </form>
    </div>
@endsection