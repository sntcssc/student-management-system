<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class TransactionManagementTest extends TestCase
{
    public function test_finance_user_can_access_transaction_list()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('manage-transactions');

        $response = $this->actingAs($user)->get(route('transactions.index'));

        $response->assertStatus(200);
    }

    public function test_non_finance_user_cannot_access_transaction_list()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('transactions.index'));

        $response->assertStatus(403);
    }
}