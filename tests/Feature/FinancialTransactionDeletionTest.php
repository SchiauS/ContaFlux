<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\FinancialAccount;
use App\Models\FinancialTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FinancialTransactionDeletionTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_delete_own_company_transaction(): void
    {
        $company = Company::create([
            'name' => 'Test Co',
            'fiscal_code' => 'FC123',
            'currency' => 'RON',
            'timezone' => 'Europe/Bucharest',
        ]);

        $account = FinancialAccount::create([
            'company_id' => $company->id,
            'code' => '401',
            'name' => 'Revenue',
        ]);

        $user = User::factory()->create([
            'company_id' => $company->id,
        ]);

        $transaction = FinancialTransaction::create([
            'company_id' => $company->id,
            'financial_account_id' => $account->id,
            'direction' => 'credit',
            'amount' => 100,
            'currency' => 'RON',
            'occurred_at' => now(),
        ]);

        $response = $this->actingAs($user)
            ->from(route('transactions.index'))
            ->delete(route('transactions.destroy', $transaction));

        $response->assertRedirect(route('transactions.index'));
        $this->assertDatabaseMissing('financial_transactions', ['id' => $transaction->id]);
    }
}
