<div class="container mx-auto p-4">
    <h2 class="text-xl font-bold mb-4">Create Permission</h2>
    <form wire:submit.prevent="create" class="space-y-4">
        <div>
            <label for="name" class="block text-sm font-medium">Permission Name</label>
            <input wire:model="name" type="text" class="w-full p-2 rounded dark:bg-gray-800 dark:text-gray-200">
            @error('name') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>
        <button type="submit" class="bg-blue-500 text-white p-2 rounded">Create Permission</button>
    </form>
    @include('components.flash-message')
</div>