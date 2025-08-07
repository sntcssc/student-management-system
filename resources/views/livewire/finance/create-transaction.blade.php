<div class="container mx-auto p-4">
    <h2 class="text-xl font-bold mb-4">{{ __('Create Transaction') }}</h2>
    <form wire:submit.prevent="create" class="space-y-4">
        @if ($isAdmin)
        <div>
            <label for="user_id" class="block text-sm font-medium">{{ __('Select User') }}</label>
            <select wire:model="user_id" class="w-full p-2 rounded dark:bg-gray-800 dark:text-gray-200">
                <option value="">{{ __('Select User') }}</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
                @endforeach
            </select>
            @error('user_id') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>
        @endif
        <div>
            <label for="type" class="block text-sm font-medium">{{ __('Type') }}</label>
            <select wire:model="type" class="w-full p-2 rounded dark:bg-gray-800 dark:text-gray-200">
                <option value="">{{ __('Select Type') }}</option>
                <option value="payment">{{ __('Payment') }}</option>
                <option value="deposit">{{ __('Deposit') }}</option>
                <option value="refund">{{ __('Refund') }}</option>
            </select>
            @error('type') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>
        <div>
            <label for="amount" class="block text-sm font-medium">{{ __('Amount') }}</label>
            <input wire:model="amount" type="number" step="0.01" class="w-full p-2 rounded dark:bg-gray-800 dark:text-gray-200">
            @error('amount') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>
        <div>
            <label for="payment_method" class="block text-sm font-medium">{{ __('Payment Method') }}</label>
            <select wire:model="payment_method" class="w-full p-2 rounded dark:bg-gray-800 dark:text-gray-200">
                <option value="">{{ __('Select Payment Method') }}</option>
                <option value="cash">{{ __('Cash') }}</option>
                <option value="credit_card">{{ __('Credit Card') }}</option>
                <option value="debit_card">{{ __('Debit Card') }}</option>
                <option value="bank_transfer">{{ __('Bank Transfer') }}</option>
                <option value="upi">{{ __('UPI') }}</option>
            </select>
            @error('payment_method') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>
        <div>
            <label for="status" class="block text-sm font-medium">{{ __('Status') }}</label>
            <select wire:model="status" class="w-full p-2 rounded dark:bg-gray-800 dark:text-gray-200">
                <option value="">{{ __('Select Status') }}</option>
                <option value="pending">{{ __('Pending') }}</option>
                <option value="completed">{{ __('Completed') }}</option>
                <option value="failed">{{ __('Failed') }}</option>
            </select>
            @error('status') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>
        <div>
            <label for="transaction_date" class="block text-sm font-medium">{{ __('Transaction Date') }}</label>
            <input wire:model="transaction_date" type="datetime-local" class="w-full p-2 rounded dark:bg-gray-800 dark:text-gray-200">
            @error('transaction_date') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>
        <div>
            <label for="reference_id" class="block text-sm font-medium">{{ __('Reference ID') }}</label>
            <input wire:model="reference_id" type="text" class="w-full p-2 rounded dark:bg-gray-800 dark:text-gray-200">
            @error('reference_id') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>
        <div>
            <label for="notes" class="block text-sm font-medium">{{ __('Notes') }}</label>
            <textarea wire:model="notes" class="w-full p-2 rounded dark:bg-gray-800 dark:text-gray-200"></textarea>
            @error('notes') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>
        <button type="submit" class="bg-blue-500 text-white p-2 rounded">{{ __('Create Transaction') }}</button>
    </form>
    @include('components.flash-message')
</div>