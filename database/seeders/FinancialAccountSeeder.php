<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\FinancialAccount;
use Illuminate\Database\Seeder;

class FinancialAccountSeeder extends Seeder
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

        $accounts = [
            [
                'code' => '101',
                'name' => 'Casa',
                'type' => 'asset',
                'category' => 'cash',
                'description' => 'Numerar în casierie',
            ],
            [
                'code' => '5121',
                'name' => 'Conturi la bănci în lei',
                'type' => 'asset',
                'category' => 'bank',
                'description' => 'Disponibil în cont curent RON',
            ],
            [
                'code' => '418',
                'name' => 'Clienți facturi emise',
                'type' => 'asset',
                'category' => 'receivables',
                'description' => 'Facturi de încasat de la clienți',
            ],
            [
                'code' => '401',
                'name' => 'Furnizori',
                'type' => 'liability',
                'category' => 'payables',
                'description' => 'Datorii către furnizori',
            ],
            [
                'code' => '704',
                'name' => 'Venituri din servicii prestate',
                'type' => 'revenue',
                'category' => 'revenue',
                'description' => 'Venituri din servicii oferite clienților',
            ],
            [
                'code' => '628',
                'name' => 'Cheltuieli cu serviciile executate de terți',
                'type' => 'expense',
                'category' => 'expense',
                'description' => 'Marketing, licențe software și alte servicii',
            ],
        ];

        foreach ($accounts as $account) {
            FinancialAccount::updateOrCreate(
                [
                    'company_id' => $company->id,
                    'code' => $account['code'],
                ],
                array_merge($account, ['company_id' => $company->id]),
            );
        }
    }
}
