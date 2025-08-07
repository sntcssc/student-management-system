<div class="container mx-auto p-4">
    <h2 class="text-xl font-bold mb-4">Import/Export Users</h2>
    <div class="space-y-4">
        <div>
            <h3 class="text-lg font-semibold">Import Users</h3>
            <form wire:submit.prevent="import" class="space-y-2">
                <input wire:model="file" type="file" class="w-full p-2 rounded dark:bg-gray-800 dark:text-gray-200">
                @error('file') <span class="text-red-500">{{ $message }}</span> @enderror
                <button type="submit" class="bg-blue-500 text-white p-2 rounded">Import</button>
            </form>
        </div>
        <div>
            <h3 class="text-lg font-semibold">Export Users</h3>
            <button wire:click="export" class="bg-green-500 text-white p-2 rounded">Export to Excel</button>
            <a href="{{ route('users.export-pdf') }}" class="bg-purple-500 text-white p-2 rounded">Export to PDF</a>
        </div>
    </div>
    @if (session('message'))
        <div class="mt-4 p-2 bg-green-100 text-green-800">{{ session('message') }}</div>
    @endif
    @if (session('error'))
        <div class="mt-4 p-2 bg-red-100 text-red-800">{{ session('error') }}</div>
    @endif
</div>