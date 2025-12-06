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
            'name' => 'URA Development SRL',
            'fiscal_code' => 'RO98765432',
            'currency' => 'RON',
            'fiscal_year_start' => '2025-01-01',
            'timezone' => 'Europe/Bucharest',
        ]);

        $accounts = FinancialAccount::where('company_id', $company->id)
            ->get()
            ->keyBy('code');

        $start = Carbon::now('Europe/Bucharest')->subYear()->startOfYear();
        $end = Carbon::now('Europe/Bucharest')->endOfYear();

        $revenueStreams = [
            'Contract de mentenanță enterprise',
            'Dezvoltare produs custom',
            'Servicii de consultanță arhitectură cloud',
        ];

        $expensePartners = [
            'Furnizor marketing digital',
            'Furnizor licențe și abonamente',
            'Partener outsourcing QA',
        ];

        $cursor = $start->copy();
        $transactions = [];

        while ($cursor->lte($end)) {
            $monthName = $cursor->isoFormat('MMMM YYYY');
            $monthFactor = 1 + ($cursor->month % 3) * 0.02;

            $retainAmount = round(85000 * $monthFactor, 2);
            $projectAmount = round(55000 * $monthFactor, 2);
            $consultingAmount = round(28000 * $monthFactor, 2);
            $deferredAmount = round(20000 * $monthFactor, 2);

            $transactions[] = [
                'financial_account_code' => '5121',
                'counterparty' => 'Banca Comercială',
                'description' => "Încasare {$monthName} {$revenueStreams[0]}",
                'direction' => 'debit',
                'amount' => $retainAmount,
                'currency' => 'RON',
                'tax_rate' => 0,
                'occurred_at' => $cursor->copy()->day(10)->setTime(11, 30),
            ];
            $transactions[] = [
                'financial_account_code' => '704',
                'counterparty' => 'Client enterprise',
                'description' => "Venit {$revenueStreams[0]} {$monthName}",
                'direction' => 'credit',
                'amount' => $retainAmount,
                'currency' => 'RON',
                'tax_rate' => 19.0,
                'occurred_at' => $cursor->copy()->day(10)->setTime(11, 30),
            ];

            $transactions[] = [
                'financial_account_code' => '101',
                'counterparty' => 'Client numerar',
                'description' => "Încasare numerar {$revenueStreams[1]} {$monthName}",
                'direction' => 'debit',
                'amount' => $projectAmount,
                'currency' => 'RON',
                'tax_rate' => 0,
                'occurred_at' => $cursor->copy()->day(5)->setTime(9, 15),
            ];
            $transactions[] = [
                'financial_account_code' => '704',
                'counterparty' => 'Client scale-up',
                'description' => "Venit {$revenueStreams[1]} {$monthName}",
                'direction' => 'credit',
                'amount' => $projectAmount,
                'currency' => 'RON',
                'tax_rate' => 19.0,
                'occurred_at' => $cursor->copy()->day(5)->setTime(9, 15),
            ];

            $transactions[] = [
                'financial_account_code' => '5121',
                'counterparty' => 'Client consultanță',
                'description' => "Încasare {$revenueStreams[2]} {$monthName}",
                'direction' => 'debit',
                'amount' => $consultingAmount,
                'currency' => 'RON',
                'tax_rate' => 0,
                'occurred_at' => $cursor->copy()->day(18)->setTime(14, 20),
            ];
            $transactions[] = [
                'financial_account_code' => '704',
                'counterparty' => 'Client consultanță',
                'description' => "Venit {$revenueStreams[2]} {$monthName}",
                'direction' => 'credit',
                'amount' => $consultingAmount,
                'currency' => 'RON',
                'tax_rate' => 19.0,
                'occurred_at' => $cursor->copy()->day(18)->setTime(14, 20),
            ];

            $transactions[] = [
                'financial_account_code' => '5121',
                'counterparty' => 'Client enterprise',
                'description' => "Încasare avans {$monthName}",
                'direction' => 'debit',
                'amount' => $deferredAmount,
                'currency' => 'RON',
                'tax_rate' => 0,
                'occurred_at' => $cursor->copy()->day(3)->setTime(16, 45),
            ];
            $transactions[] = [
                'financial_account_code' => '419',
                'counterparty' => 'Client enterprise',
                'description' => "Venituri facturate în avans {$monthName}",
                'direction' => 'credit',
                'amount' => $deferredAmount,
                'currency' => 'RON',
                'tax_rate' => 19.0,
                'occurred_at' => $cursor->copy()->day(3)->setTime(16, 45),
            ];

            $marketingExpense = round(7200 * $monthFactor, 2);
            $toolsExpense = round(5800 * $monthFactor, 2);
            $outsourcingExpense = round(9600 * $monthFactor, 2);

            $transactions[] = [
                'financial_account_code' => '628',
                'counterparty' => $expensePartners[0],
                'description' => "Campanie marketing {$monthName}",
                'direction' => 'debit',
                'amount' => $marketingExpense,
                'currency' => 'RON',
                'tax_rate' => 19.0,
                'occurred_at' => $cursor->copy()->day(7)->setTime(12, 0),
            ];
            $transactions[] = [
                'financial_account_code' => '401',
                'counterparty' => $expensePartners[0],
                'description' => "Datorie furnizor marketing {$monthName}",
                'direction' => 'credit',
                'amount' => $marketingExpense,
                'currency' => 'RON',
                'tax_rate' => 19.0,
                'occurred_at' => $cursor->copy()->day(7)->setTime(12, 0),
            ];

            $transactions[] = [
                'financial_account_code' => '628',
                'counterparty' => $expensePartners[1],
                'description' => "Licențe software {$monthName}",
                'direction' => 'debit',
                'amount' => $toolsExpense,
                'currency' => 'RON',
                'tax_rate' => 19.0,
                'occurred_at' => $cursor->copy()->day(15)->setTime(10, 15),
            ];
            $transactions[] = [
                'financial_account_code' => '401',
                'counterparty' => $expensePartners[1],
                'description' => "Factura licențe {$monthName}",
                'direction' => 'credit',
                'amount' => $toolsExpense,
                'currency' => 'RON',
                'tax_rate' => 19.0,
                'occurred_at' => $cursor->copy()->day(15)->setTime(10, 15),
            ];

            $transactions[] = [
                'financial_account_code' => '628',
                'counterparty' => $expensePartners[2],
                'description' => "Servicii outsourcing QA {$monthName}",
                'direction' => 'debit',
                'amount' => $outsourcingExpense,
                'currency' => 'RON',
                'tax_rate' => 19.0,
                'occurred_at' => $cursor->copy()->day(20)->setTime(13, 40),
            ];
            $transactions[] = [
                'financial_account_code' => '401',
                'counterparty' => $expensePartners[2],
                'description' => "Datorie outsourcing {$monthName}",
                'direction' => 'credit',
                'amount' => $outsourcingExpense,
                'currency' => 'RON',
                'tax_rate' => 19.0,
                'occurred_at' => $cursor->copy()->day(20)->setTime(13, 40),
            ];

            if ($cursor->month % 3 === 0) {
                $equipmentAmount = round(9200 * $monthFactor, 2);

                $transactions[] = [
                    'financial_account_code' => '231',
                    'counterparty' => 'Retail IT',
                    'description' => "Achiziție echipamente {$monthName}",
                    'direction' => 'debit',
                    'amount' => $equipmentAmount,
                    'currency' => 'RON',
                    'tax_rate' => 19.0,
                    'occurred_at' => $cursor->copy()->day(8)->setTime(11, 0),
                ];
                $transactions[] = [
                    'financial_account_code' => '401',
                    'counterparty' => 'Retail IT',
                    'description' => "Datorie echipamente {$monthName}",
                    'direction' => 'credit',
                    'amount' => $equipmentAmount,
                    'currency' => 'RON',
                    'tax_rate' => 19.0,
                    'occurred_at' => $cursor->copy()->day(8)->setTime(11, 0),
                ];
            }

            $cursor->addMonth();
        }

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
