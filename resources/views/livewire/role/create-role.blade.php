<div class="container mx-auto p-4">
    <form wire:submit.prevent="create" class="space-y-4">
        <div>
            <label for="name" class="block text-sm font-medium">Role Name</label>
            <input wire:model="name" type="text" class="w-full p-2 rounded dark:bg-gray-800 dark:text-gray-200">
            @error('name') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium">Permissions</label>
            @foreach ($allPermissions as $permission)
                <label>
                    <input wire:model="permissions" type="checkbox" value="{{ $permission->name }}"> {{ $permission->name }}
                </label>
            @endforeach
            @error('permissions') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>
        <button type="submit" class="bg-blue-500 text-white p-2 rounded">Create Role</button>
    </form>
    @if (session('message'))
        <div class="mt-4 p-2 bg-green-100 text-green-800">{{ session('message') }}</div>
    @endif
    @if (session('error'))
        <div class="mt-4 p-2 bg-red-100 text-red-800">{{ session('error') }}</div>
    @endif
</div>