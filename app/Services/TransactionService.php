<?php

namespace App\Services;

use App\Repositories\Interfaces\TransactionRepositoryInterface;
use App\Logging\UserActivityLogger;
use Illuminate\Support\Facades\Auth;

class TransactionService
{
    protected $transactionRepository;

    public function __construct(TransactionRepositoryInterface $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    public function getAllTransactions(array $filters)
    {
        return $this->transactionRepository->getAllTransactions($filters);
    }

    public function find($id)
    {
        return $this->transactionRepository->find($id);
    }

    public function createTransaction(array $data)
    {
        // Ensure non-admins can only create transactions for themselves
        if (!Auth::user()->hasRole('admin') && $data['user_id'] != Auth::id()) {
            throw new \Exception(__('Unauthorized to create transaction for another user.'));
        }

        $transaction = $this->transactionRepository->create($data);
        UserActivityLogger::log(Auth::id(), 'create_transaction', "Created transaction ID {$transaction->id}");
        return $transaction;
    }

    public function updateTransaction($id, array $data)
    {
        // Ensure non-admins can only update their own transactions
        $transaction = $this->transactionRepository->find($id);
        if (!Auth::user()->hasRole('admin') && $transaction->user_id != Auth::id()) {
            throw new \Exception(__('Unauthorized to update this transaction.'));
        }

        $transaction = $this->transactionRepository->update($id, $data);
        UserActivityLogger::log(Auth::id(), 'update_transaction', "Updated transaction ID {$id}");
        return $transaction;
    }
}