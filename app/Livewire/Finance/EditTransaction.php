<?php

namespace App\Livewire\Finance;

use App\Models\User;
use App\Services\TransactionService;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class EditTransaction extends Component
{
    public $transactionId, $type, $amount, $payment_method, $status, $transaction_date, $reference_id, $notes, $user_id;
    public $users = [];
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

    public function mount($id, TransactionService $transactionService)
    {
        $this->isAdmin = auth::user()->hasRole('admin');
        $transaction = $transactionService->find($id);
        $this->transactionId = $transaction->id;
        $this->type = $transaction->type;
        $this->amount = $transaction->amount;
        $this->payment_method = $transaction->payment_method;
        $this->status = $transaction->status;
        $this->transaction_date = $transaction->transaction_date->format('Y-m-d\TH:i');
        $this->reference_id = $transaction->reference_id;
        $this->notes = $transaction->notes;
        $this->user_id = $transaction->user_id;

        if ($this->isAdmin) {
            $this->users = User::select('id', 'first_name', 'last_name')->get();
        } else {
            $this->user_id = auth::id();
        }
    }

    public function update(TransactionService $transactionService)
    {
        $this->validate();
        try {
            $transactionService->updateTransaction($this->transactionId, [
                'user_id' => $this->user_id,
                'type' => $this->type,
                'amount' => $this->amount,
                'payment_method' => $this->payment_method,
                'status' => $this->status,
                'transaction_date' => $this->transaction_date,
                'reference_id' => $this->reference_id,
                'notes' => $this->notes,
            ]);
            session()->flash('message', __('Transaction updated successfully.'));
            $this->redirectRoute('transactions.index');
        } catch (\Exception $e) {
            session()->flash('error', __('Failed to update transaction: :message', ['message' => $e->getMessage()]));
        }
    }

    public function render()
    {
        return view('livewire.finance.edit-transaction');
    }
}