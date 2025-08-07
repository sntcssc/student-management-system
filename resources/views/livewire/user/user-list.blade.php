<div class="container mx-auto p-4">
    <div class="mb-4">
        <input wire:model.live="search" type="text" placeholder="Search users..." class="w-full p-2 rounded dark:bg-gray-800 dark:text-gray-200">
    </div>
    <table class="w-full table-auto">
        <thead>
            <tr class="bg-gray-200 dark:bg-gray-700">
                <th wire:click="sortBy('first_name')" class="p-2 cursor-pointer">Name</th>
                <th wire:click="sortBy('email')" class="p-2 cursor-pointer">Email</th>
                <th class="p-2">Roles</th>
                <th class="p-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr class="border-b dark:border-gray-600">
                    <td class="p-2">{{ $user->first_name }} {{ $user->last_name }}</td>
                    <td class="p-2">{{ $user->email }}</td>
                    <td class="p-2">{{ $user->roles->pluck('name')->implode(', ') }}</td>
                    <td class="p-2">
                        <a href="{{ route('users.edit', $user->id) }}" class="text-blue-500">Edit</a>
                        @if ($user->trashed())
                            <button wire:click="restore({{ $user->id }})" class="text-green-500">Restore</button>
                        @else
                            <button wire:click="delete({{ $user->id }})" class="text-red-500">Delete</button>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $users->links() }}
</div>