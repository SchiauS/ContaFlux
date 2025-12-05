<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\FinancialAccount;
use App\Models\FinancialTransaction;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class FinancialTransactionSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::first() ?? Company::create([
            'name' => 'Nebula Dev Studio SRL',
            'fiscal_code' => 'RO98765432',
            'currency' => 'RON',
            'fiscal_year_start' => '2025-01-01',
            'timezone' => 'Europe/Bucharest',
        ]);

        $accounts = FinancialAccount::where('company_id', $company->id)
            ->get()
            ->keyBy('code');

        $currentDate = Carbon::now('Europe/Bucharest');
        $year = $currentDate->year;

        $transactions = [
            [
                'financial_account_code' => '101',
                'counterparty' => 'Client numerar',
                'description' => 'Încasare numerar pentru consultanță startup',
                'direction' => 'debit',
                'amount' => 3100.00,
                'currency' => 'RON',
                'tax_rate' => 0,
                'occurred_at' => Carbon::create($year, 1, 10, 9, 30)->toDateTimeString(),
            ],
            [
                'financial_account_code' => '5121',
                'counterparty' => 'Banca Comercială',
                'description' => 'Încasare factură #NDS-102 prin bancă',
                'direction' => 'debit',
                'amount' => 9200.00,
                'currency' => 'RON',
                'tax_rate' => 0,
                'occurred_at' => Carbon::create($year, 2, 14, 12, 10)->toDateTimeString(),
            ],
            [
                'financial_account_code' => '704',
                'counterparty' => 'Client Enterprise',
                'description' => 'Venituri din abonament lunar dezvoltare și mentenanță',
                'direction' => 'credit',
                'amount' => 10500.00,
                'currency' => 'RON',
                'tax_rate' => 19.0,
                'occurred_at' => Carbon::create($year, 2, 14, 12, 10)->toDateTimeString(),
            ],
            [
                'financial_account_code' => '419',
                'counterparty' => 'Client Enterprise',
                'description' => 'Facturare în avans pentru sprint Q2',
                'direction' => 'credit',
                'amount' => 18000.00,
                'currency' => 'RON',
                'tax_rate' => 19.0,
                'occurred_at' => Carbon::create($year, 3, 5, 10, 0)->toDateTimeString(),
            ],
            [
                'financial_account_code' => '418',
                'counterparty' => 'Client Enterprise',
                'description' => 'Recunoaștere creanță pentru livrare Q1',
                'direction' => 'debit',
                'amount' => 14500.00,
                'currency' => 'RON',
                'tax_rate' => 19.0,
                'occurred_at' => Carbon::create($year, 3, 28, 16, 15)->toDateTimeString(),
            ],
            [
                'financial_account_code' => '628',
                'counterparty' => 'Furnizor marketing',
                'description' => 'Campanie ads trimestru II',
                'direction' => 'debit',
                'amount' => 3500.00,
                'currency' => 'RON',
                'tax_rate' => 19.0,
                'occurred_at' => Carbon::create($year, 4, 12, 15, 45)->toDateTimeString(),
            ],
            [
                'financial_account_code' => '401',
                'counterparty' => 'Furnizor licențe',
                'description' => 'Factura licențe software trimestrul II',
                'direction' => 'credit',
                'amount' => 2100.00,
                'currency' => 'RON',
                'tax_rate' => 19.0,
                'occurred_at' => Carbon::create($year, 4, 20, 8, 20)->toDateTimeString(),
            ],
            [
                'financial_account_code' => '231',
                'counterparty' => 'Retail IT',
                'description' => 'Avans achiziție laptopuri noi',
                'direction' => 'debit',
                'amount' => 12400.00,
                'currency' => 'RON',
                'tax_rate' => 19.0,
                'occurred_at' => Carbon::create($year, $currentDate->month, 3, 11, 30)->toDateTimeString(),
            ],
            [
                'financial_account_code' => '421',
                'counterparty' => 'Angajați Nebula Dev Studio',
                'description' => 'Înregistrare salarii nete',
                'direction' => 'credit',
                'amount' => 48000.00,
                'currency' => 'RON',
                'tax_rate' => 0,
                'occurred_at' => Carbon::create($year, $currentDate->month, 25, 17, 0)->toDateTimeString(),
            ],
            [
                'financial_account_code' => '431',
                'counterparty' => 'Casa de Pensii',
                'description' => 'Contribuții CAS aferente salariilor',
                'direction' => 'credit',
                'amount' => 12000.00,
                'currency' => 'RON',
                'tax_rate' => 0,
                'occurred_at' => Carbon::create($year, $currentDate->month, 25, 17, 5)->toDateTimeString(),
            ],
            [
                'financial_account_code' => '5121',
                'counterparty' => 'Banca Comercială',
                'description' => 'Încasare finală proiect enterprise',
                'direction' => 'debit',
                'amount' => 21500.00,
                'currency' => 'RON',
                'tax_rate' => 0,
                'occurred_at' => Carbon::create($year, 9, 8, 10, 50)->toDateTimeString(),
            ],
            [
                'financial_account_code' => '704',
                'counterparty' => 'Client Enterprise',
                'description' => 'Venituri din livrare milestone final',
                'direction' => 'credit',
                'amount' => 21500.00,
                'currency' => 'RON',
                'tax_rate' => 19.0,
                'occurred_at' => Carbon::create($year, 9, 8, 10, 50)->toDateTimeString(),
            ],
            [
                'financial_account_code' => '101',
                'counterparty' => 'Client numerar',
                'description' => 'Încasare avans contract suport',
                'direction' => 'debit',
                'amount' => 1800.00,
                'currency' => 'RON',
                'tax_rate' => 0,
                'occurred_at' => Carbon::create($year, 11, 2, 9, 5)->toDateTimeString(),
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
