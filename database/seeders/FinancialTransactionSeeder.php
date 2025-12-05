<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\FinancialAccount;
use App\Models\FinancialTransaction;
use Illuminate\Database\Seeder;

class FinancialTransactionSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::first() ?? Company::create([
            'name' => 'ContaFlux Demo SRL',
            'fiscal_code' => 'RO12345678',
            'currency' => 'RON',
            'fiscal_year_start' => '2025-01-01',
            'timezone' => 'Europe/Bucharest',
        ]);

        $accounts = FinancialAccount::where('company_id', $company->id)
            ->get()
            ->keyBy('code');

        $transactions = [
            [
                'financial_account_code' => '101',
                'counterparty' => 'Client numerar',
                'description' => 'Încasare numerar servicii de consultanță',
                'direction' => 'debit',
                'amount' => 2500.00,
                'currency' => 'RON',
                'tax_rate' => 0,
                'occurred_at' => '2025-01-10 09:30:00',
            ],
            [
                'financial_account_code' => '5121',
                'counterparty' => 'Banca Comercială',
                'description' => 'Încasare factură #CFX-102 prin bancă',
                'direction' => 'debit',
                'amount' => 7800.00,
                'currency' => 'RON',
                'tax_rate' => 0,
                'occurred_at' => '2025-01-18 12:10:00',
            ],
            [
                'financial_account_code' => '704',
                'counterparty' => 'Client Enterprise',
                'description' => 'Venituri din abonament lunar',
                'direction' => 'credit',
                'amount' => 8200.00,
                'currency' => 'RON',
                'tax_rate' => 19.0,
                'occurred_at' => '2025-01-18 12:10:00',
            ],
            [
                'financial_account_code' => '628',
                'counterparty' => 'Furnizor marketing',
                'description' => 'Campanie ads trimestru I',
                'direction' => 'debit',
                'amount' => 3200.00,
                'currency' => 'RON',
                'tax_rate' => 19.0,
                'occurred_at' => '2025-01-22 15:45:00',
            ],
            [
                'financial_account_code' => '401',
                'counterparty' => 'Furnizor licențe',
                'description' => 'Factura licențe software ianuarie',
                'direction' => 'credit',
                'amount' => 1450.00,
                'currency' => 'RON',
                'tax_rate' => 19.0,
                'occurred_at' => '2025-01-23 08:20:00',
            ],
            [
                'financial_account_code' => '408',
                'counterparty' => 'Transportator',
                'description' => 'Servicii livrare – factură lipsă',
                'direction' => 'credit',
                'amount' => 620.00,
                'currency' => 'RON',
                'tax_rate' => 19.0,
                'occurred_at' => '2025-01-25 10:10:00',
            ],
            [
                'financial_account_code' => '419',
                'counterparty' => 'Client avans',
                'description' => 'Avans contract mentenanță',
                'direction' => 'credit',
                'amount' => 5000.00,
                'currency' => 'RON',
                'tax_rate' => 0,
                'occurred_at' => '2025-01-28 11:00:00',
            ],
            [
                'financial_account_code' => '421',
                'counterparty' => 'Salariați',
                'description' => 'Salarii nete datorate luna ianuarie',
                'direction' => 'credit',
                'amount' => 12000.00,
                'currency' => 'RON',
                'tax_rate' => 0,
                'occurred_at' => '2025-01-31 18:00:00',
            ],
            [
                'financial_account_code' => '4311',
                'counterparty' => 'CAS',
                'description' => 'Contribuții CAS salarii ianuarie',
                'direction' => 'credit',
                'amount' => 3000.00,
                'currency' => 'RON',
                'tax_rate' => 0,
                'occurred_at' => '2025-01-31 18:05:00',
            ],
            [
                'financial_account_code' => '4371',
                'counterparty' => 'CAM',
                'description' => 'Contribuția asiguratorie pentru muncă',
                'direction' => 'credit',
                'amount' => 600.00,
                'currency' => 'RON',
                'tax_rate' => 0,
                'occurred_at' => '2025-01-31 18:06:00',
            ],
            [
                'financial_account_code' => '4427',
                'counterparty' => 'ANAF',
                'description' => 'TVA colectată aferentă vânzărilor',
                'direction' => 'credit',
                'amount' => 1558.00,
                'currency' => 'RON',
                'tax_rate' => 0,
                'occurred_at' => '2025-01-18 12:10:00',
            ],
            [
                'financial_account_code' => '446',
                'counterparty' => 'Buget local',
                'description' => 'Taxă mediu trimestrul I',
                'direction' => 'credit',
                'amount' => 450.00,
                'currency' => 'RON',
                'tax_rate' => 0,
                'occurred_at' => '2025-02-05 09:00:00',
            ],
        ];

        foreach ($transactions as $transaction) {
            $account = $accounts[$transaction['financial_account_code']] ?? null;

            if (! $account) {
                continue;
            }

            FinancialTransaction::updateOrCreate(
                [
                    'company_id' => $company->id,
                    'financial_account_id' => $account->id,
                    'description' => $transaction['description'],
                    'occurred_at' => $transaction['occurred_at'],
                ],
                [
                    'counterparty' => $transaction['counterparty'],
                    'direction' => $transaction['direction'],
                    'amount' => $transaction['amount'],
                    'currency' => $transaction['currency'],
                    'tax_rate' => $transaction['tax_rate'],
                    'metadata' => null,
                ],
            );
        }
    }
}
