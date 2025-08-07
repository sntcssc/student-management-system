@if (session('message'))
    <div class="p-4 mb-4 bg-green-100 text-green-800 rounded">
        {{ session('message') }}
    </div>
@endif
@if (session('error'))
    <div class="p-4 mb-4 bg-red-100 text-red-800 rounded">
        {{ session('error') }}
    </div>
@endif