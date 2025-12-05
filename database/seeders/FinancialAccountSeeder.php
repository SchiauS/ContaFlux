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
            'name' => 'Nebula Dev Studio SRL',
            'fiscal_code' => 'RO98765432',
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
                'description' => 'Numerar în casierie (cont activ)',
            ],
            [
                'code' => '5121',
                'name' => 'Conturi la bănci în lei',
                'type' => 'asset',
                'category' => 'bank',
                'description' => 'Disponibil în cont curent RON (cont activ)',
            ],
            [
                'code' => '418',
                'name' => 'Clienți facturi emise',
                'type' => 'asset',
                'category' => 'receivables',
                'description' => 'Facturi de încasat de la clienți (cont activ)',
            ],
            [
                'code' => '401',
                'name' => 'Furnizori',
                'type' => 'liability',
                'category' => 'payables',
                'description' => 'Datorii către furnizori (cont pasiv)',
            ],
            [
                'code' => '419',
                'name' => 'Venituri facturate în avans',
                'type' => 'liability',
                'category' => 'deferred_revenue',
                'description' => 'Facturi emise în avans pentru servicii (cont pasiv)',
            ],
            [
                'code' => '704',
                'name' => 'Venituri din servicii prestate',
                'type' => 'revenue',
                'category' => 'revenue',
                'description' => 'Venituri din dezvoltare software și consultanță',
            ],
            [
                'code' => '628',
                'name' => 'Cheltuieli cu serviciile executate de terți',
                'type' => 'expense',
                'category' => 'expense',
                'description' => 'Marketing, licențe software și alte servicii',
            ],
            [
                'code' => '431',
                'name' => 'Contribuții asigurări sociale',
                'type' => 'liability',
                'category' => 'taxes',
                'description' => 'CAS datorată pentru angajați (cont pasiv)',
            ],
            [
                'code' => '421',
                'name' => 'Personal – salarii datorate',
                'type' => 'liability',
                'category' => 'payroll',
                'description' => 'Salarii nete de plată (cont pasiv)',
            ],
            [
                'code' => '231',
                'name' => 'Imobilizări corporale în curs',
                'type' => 'asset',
                'category' => 'fixed_assets',
                'description' => 'Laptopuri și echipamente IT în curs de achiziție (cont activ)',
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
