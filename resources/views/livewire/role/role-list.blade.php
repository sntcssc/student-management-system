<div class="container mx-auto p-4">
    <div class="mb-4">
        <input wire:model.live="search" type="text" placeholder="Search roles..." class="w-full p-2 rounded dark:bg-gray-800 dark:text-gray-200">
    </div>
    <table class="w-full table-auto">
        <thead>
            <tr class="bg-gray-200 dark:bg-gray-700">
                <th wire:click="sortBy('name')" class="p-2 cursor-pointer">Name</th>
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
                        <button wire:click="delete({{ $role->id }})" class="text-red-500">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $roles->links() }}
</div>