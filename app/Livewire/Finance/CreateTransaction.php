<?php

namespace App\Livewire\Finance;

use App\Models\User;
use App\Services\TransactionService;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class CreateTransaction extends Component
{
    public $type, $amount, $payment_method, $status, $transaction_date, $reference_id, $notes;
    public $user_id; // For admin to select user
    public $users = []; // List of users for admin dropdown
    public $isAdmin = false;

    protected $rules = [
        'type' => 'required|in:payment,deposit,refund',
        'amount' => 'required|numeric|min:0',
        'payment_method' => 'required|in:cash,credit_card,debit_card,bank_transfer,upi',
        'status' => 'required|in:pending,completed,failed',
        'transaction_date' => 'required|date',
        'reference_id' => 'nullable|string|max:255',
        'notes' => 'nullable|string',
        'user_id' => 'required|exists:users,id',
    ];

    public function mount()
    {
        $this->isAdmin = auth::user()->hasRole('admin');
        if ($this->isAdmin) {
            $this->users = User::select('id', 'first_name', 'last_name')->get();
        } else {
            $this->user_id = auth::id();
        }
    }

    public function create(TransactionService $transactionService)
    {
        $this->validate();
        try {
            $transactionService->createTransaction([
                'user_id' => $this->user_id,
                'type' => $this->type,
                'amount' => $this->amount,
                'payment_method' => $this->payment_method,
                'status' => $this->status,
                'transaction_date' => $this->transaction_date,
                'reference_id' => $this->reference_id,
                'notes' => $this->notes,
            ]);
            session()->flash('message', __('Transaction created successfully.'));
            $this->reset();
            $this->redirectRoute('transactions.index');
        } catch (\Exception $e) {
            session()->flash('error', __('Failed to create transaction: :message', ['message' => $e->getMessage()]));
        }
    }

    public function render()
    {
        return view('livewire.finance.create-transaction');
    }
}