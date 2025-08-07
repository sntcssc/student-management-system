<?php

namespace App\Repositories;

use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class TransactionRepository implements TransactionRepositoryInterface
{
    public function getAllTransactions(array $filters)
    {
        $query = Transaction::query();

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('type', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('payment_method', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('status', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('reference_id', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('notes', 'like', '%' . $filters['search'] . '%');
            });
        }

        if (!empty($filters['sort']) && !empty($filters['direction'])) {
            $query->orderBy($filters['sort'], $filters['direction']);
        }

        return $query->where('user_id', auth::id())->paginate(10);
    }

    public function find($id)
    {
        return Transaction::where('user_id', auth::id())->findOrFail($id);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            return Transaction::create($data);
        });
    }

    public function update($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $transaction = $this->find($id);
            $transaction->update($data);
            return $transaction;
        });
    }
}