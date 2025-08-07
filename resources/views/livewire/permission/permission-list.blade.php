<div class="container mx-auto p-4">
    <h2 class="text-xl font-bold mb-4">Permissions</h2>
    <div class="mb-4">
        <input wire:model.live="search" type="text" placeholder="Search permissions..." class="w-full p-2 rounded dark:bg-gray-800 dark:text-gray-200">
    </div>
    <table class="w-full table-auto">
        <thead>
            <tr class="bg-gray-200 dark:bg-gray-700">
                <th wire:click="sortBy('name')" class="p-2 cursor-pointer">Name</th>
                <th class="p-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($permissions as $permission)
                <tr class="border-b dark:border-gray-600">
                    <td class="p-2">{{ $permission->name }}</td>
                    <td class="p-2">
                        <a href="{{ route('permissions.edit', $permission->id) }}" class="text-blue-500">Edit</a>
                        <button wire:click="delete({{ $permission->id }})" class="text-red-500">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $permissions->links() }}
    @include('components.flash-message')
</div>