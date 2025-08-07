<div class="container mx-auto p-4">
    <form wire:submit.prevent="create" class="space-y-4">
        <div>
            <label for="first_name" class="block text-sm font-medium">First Name</label>
            <input wire:model="first_name" type="text" class="w-full p-2 rounded dark:bg-gray-800 dark:text-gray-200">
            @error('first_name') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>
        <div>
            <label for="last_name" class="block text-sm font-medium">Last Name</label>
            <input wire:model="last_name" type="text" class="w-full p-2 rounded dark:bg-gray-800 dark:text-gray-200">
            @error('last_name') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>
        <div>
            <label for="email" class="block text-sm font-medium">Email</label>
            <input wire:model="email" type="email" class="w-full p-2 rounded dark:bg-gray-800 dark:text-gray-200">
            @error('email') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>
        <div>
            <label for="password" class="block text-sm font-medium">Password</label>
            <input wire:model="password" type="password" class="w-full p-2 rounded dark:bg-gray-800 dark:text-gray-200">
            @error('password') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>
        <div>
            <label for="category" class="block text-sm font-medium">Category</label>
            <select wire:model="category" class="w-full p-2 rounded dark:bg-gray-800 dark:text-gray-200">
                <option value="">Select Category</option>
                <option value="Unreserved">Unreserved</option>
                <option value="SC">SC</option>
                <option value="ST">ST</option>
                <option value="OBC">OBC</option>
            </select>
            @error('category') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium">Roles</label>
            @foreach ($allRoles as $role)
                <label>
                    <input wire:model="roles" type="checkbox" value="{{ $role->name }}"> {{ $role->name }}
                </label>
            @endforeach
            @error('roles') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>
        <button type="submit" class="bg-blue-500 text-white p-2 rounded">Create User</button>
    </form>
    @if (session('message'))
        <div class="mt-4 p-2 bg-green-100 text-green-800">{{ session('message') }}</div>
    @endif
    @if (session('error'))
        <div class="mt-4 p-2 bg-red-100 text-red-800">{{ session('error') }}</div>
    @endif
</div>