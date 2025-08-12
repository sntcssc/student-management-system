<?php

namespace Tests\Unit;

use App\Services\TransactionService;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use Tests\TestCase;
use Mockery;
use App\Models\Transaction;

class TransactionServiceTest extends TestCase
{
    protected $transactionService;
    protected $transactionRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->transactionRepository = Mockery::mock(TransactionRepositoryInterface::class);
        $this->transactionService = new TransactionService($this->transactionRepository);
    }

    public function test_create_transaction()
    {
        $data = [
            'user_id' => 1,
            'type' => 'payment',
            'amount' => 100.00,
            'payment_method' => 'credit_card',
            'status' => 'pending',
            'transaction_date' => now(),
            'reference_id' => 'TX123',
            'notes' => 'Test transaction',
        ];
        $transaction = new Transaction($data);
        $this->transactionRepository->shouldReceive('create')->once()->with($data)->andReturn($transaction);

        $result = $this->transactionService->createTransaction($data);

        $this->assertEquals($transaction->type, $result->type);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}