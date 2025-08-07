@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-4">
        <h2 class="text-xl font-bold mb-4">Users</h2>
        <div class="mb-4">
            <a href="{{ route('users.create') }}" class="bg-blue-500 text-white p-2 rounded">Create User</a>
        </div>
        <div class="mb-4">
            <input type="text" name="search" placeholder="Search users..." class="w-full p-2 rounded dark:bg-gray-800 dark:text-gray-200" value="{{ request('search') }}">
        </div>
        <table class="w-full table-auto">
            <thead>
                <tr class="bg-gray-200 dark:bg-gray-700">
                    <th class="p-2"><a href="?sort=name&direction={{ request('direction', 'asc') === 'asc' ? 'desc' : 'asc' }}">Name</a></th>
                    <th class="p-2">Email</th>
                    <th class="p-2">Roles</th>
                    <th class="p-2">Category</th>
                    <th class="p-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr class="border-b dark:border-gray-600">
                        <td class="p-2">{{ $user->first_name }} {{ $user->last_name }}</td>
                        <td class="p-2">{{ $user->email }}</td>
                        <td class="p-2">{{ $user->roles->pluck('name')->implode(', ') }}</td>
                        <td class="p-2">{{ $user->category ?? 'N/A' }}</td>
                        <td class="p-2">
                            <a href="{{ route('users.edit', $user->id) }}" class="text-blue-500">Edit</a>
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                            @if ($user->trashed())
                                <form action="{{ route('users.restore', $user->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-green-500">Restore</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $users->links() }}
    </div>
@endsection