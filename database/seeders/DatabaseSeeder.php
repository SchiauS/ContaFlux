<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $company = Company::firstOrCreate(
            ['name' => 'ContaFlux Demo SRL'],
            [
                'fiscal_code' => 'RO12345678',
                'currency' => 'RON',
                'fiscal_year_start' => '2025-01-01',
                'timezone' => 'Europe/Bucharest',
            ],
        );

        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
                'company_id' => $company->id,
            ],
        );

        $this->call([
            FinancialAccountSeeder::class,
            FinancialTransactionSeeder::class,
            TaskSeeder::class,
        ]);
    }
}
