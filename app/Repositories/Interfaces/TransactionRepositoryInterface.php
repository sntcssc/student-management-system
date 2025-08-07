<?php

namespace App\Repositories\Interfaces;

interface TransactionRepositoryInterface
{
    public function getAllTransactions(array $filters);
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
}