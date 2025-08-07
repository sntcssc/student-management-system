@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-4">
        <h2 class="text-xl font-bold mb-4">Roles</h2>
        <div class="mb-4">
            <a href="{{ route('roles.create') }}" class="bg-blue-500 text-white p-2 rounded">Create Role</a>
        </div>
        <div class="mb-4">
            <input type="text" name="search" placeholder="Search roles..." class="w-full p-2 rounded dark:bg-gray-800 dark:text-gray-200" value="{{ request('search') }}">
        </div>
        <table class="w-full table-auto">
            <thead>
                <tr class="bg-gray-200 dark:bg-gray-700">
                    <th class="p-2"><a href="?sort=name&direction={{ request('direction', 'asc') === 'asc' ? 'desc' : 'asc' }}">Name</a></th>
                    <th class="p-2">Permissions</th>
                    <th class="p-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($roles as $role)
                    <tr class="border-b dark:border-gray-600">
                        <td class="p-2">{{ $role->name }}</td>
                        <td class="p-2">{{ $role->permissions->pluck('name')->implode(', ') }}</td>
                        <td class="p-2">
                            <a href="{{ route('roles.edit', $role->id) }}" class="text-blue-500">Edit</a>
                            <form action="{{ route('roles.destroy', $role->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $roles->links() }}
    </div>
@endsection