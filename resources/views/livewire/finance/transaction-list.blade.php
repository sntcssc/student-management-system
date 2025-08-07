<div class="container mx-auto p-4">
    <h2 class="text-xl font-bold mb-4">{{ __('Transactions') }}</h2>
    <div class="mb-4">
        <input wire:model.live="search" type="text" placeholder="{{ __('Search transactions...') }}" class="w-full p-2 rounded dark:bg-gray-800 dark:text-gray-200">
    </div>
    <table class="w-full table-auto">
        <thead>
            <tr class="bg-gray-200 dark:bg-gray-700">
                <th wire:click="sortBy('type')" class="p-2 cursor-pointer">{{ __('Type') }}</th>
                <th wire:click="sortBy('amount')" class="p-2 cursor-pointer">{{ __('Amount') }}</th>
                <th wire:click="sortBy('payment_method')" class="p-2 cursor-pointer">{{ __('Payment Method') }}</th>
                <th wire:click="sortBy('status')" class="p-2 cursor-pointer">{{ __('Status') }}</th>
                <th wire:click="sortBy('transaction_date')" class="p-2 cursor-pointer">{{ __('Transaction Date') }}</th>
                <th class="p-2">{{ __('Reference ID') }}</th>
                <th class="p-2">{{ __('Notes') }}</th>
                <th class="p-2">{{ __('Actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $transaction)
                <tr class="border-b dark:border-gray-600">
                    <td class="p-2">{{ $transaction->type }}</td>
                    <td class="p-2">{{ number_format($transaction->amount, 2) }}</td>
                    <td class="p-2">{{ $transaction->payment_method }}</td>
                    <td class="p-2">{{ $transaction->status }}</td>
                    <td class="p-2">{{ $transaction->transaction_date->format('Y-m-d H:i:s') }}</td>
                    <td class="p-2">{{ $transaction->reference_id ?? 'N/A' }}</td>
                    <td class="p-2">{{ $transaction->notes ?? 'N/A' }}</td>
                    <td class="p-2">
                        <a href="{{ route('transactions.edit', $transaction->id) }}" class="text-blue-500">{{ __('Edit') }}</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $transactions->links() }}
    @include('components.flash-message')
</div>