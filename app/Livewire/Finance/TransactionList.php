<?php

namespace App\Livewire\Finance;

use App\Models\Transaction;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class TransactionList extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'transaction_date';
    public $sortDirection = 'desc';

    protected $queryString = ['search', 'sortField', 'sortDirection'];

    public function render()
    {
        $transactions = Transaction::where('user_id', auth::id())
            ->where(function ($query) {
                $query->where('type', 'like', '%' . $this->search . '%')
                      ->orWhere('payment_method', 'like', '%' . $this->search . '%')
                      ->orWhere('status', 'like', '%' . $this->search . '%')
                      ->orWhere('reference_id', 'like', '%' . $this->search . '%')
                      ->orWhere('notes', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.finance.transaction-list', ['transactions' => $transactions]);
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }
}