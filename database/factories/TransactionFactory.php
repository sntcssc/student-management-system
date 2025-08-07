<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'type' => $this->faker->randomElement(['payment', 'deposit', 'refund']),
            'amount' => $this->faker->randomFloat(2, 10, 10000),
            'payment_method' => $this->faker->randomElement(['cash', 'credit_card', 'debit_card', 'bank_transfer', 'upi']),
            'status' => $this->faker->randomElement(['pending', 'completed', 'failed']),
            'transaction_date' => $this->faker->dateTimeThisYear(),
            'reference_id' => $this->faker->uuid,
            'notes' => $this->faker->sentence,
        ];
    }
}