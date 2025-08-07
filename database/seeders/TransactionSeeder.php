<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\User;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $financeUser = User::where('email', 'finance@example.com')->first();
        if ($financeUser) {
            Transaction::factory()->count(10)->create(['user_id' => $financeUser->id]);
        }
    }
}
