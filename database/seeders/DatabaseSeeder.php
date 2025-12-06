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
            ['name' => 'URA Development SRL'],
            [
                'fiscal_code' => 'RO98765432',
                'currency' => 'RON',
                'fiscal_year_start' => '2025-01-01',
                'timezone' => 'Europe/Bucharest',
            ],
        );

        User::firstOrCreate(
            ['email' => 'schiau.m.sebastianadrian25@stud.rau.ro'],
            [
                'name' => 'Schiau Sebastian-Adrian',
                'password' => bcrypt('parola123'),
                'company_id' => $company->id,
            ],
        );

        $this->call([
//            FinancialAccountSeeder::class,
//            EmployeeSeeder::class,
//            FinancialTransactionSeeder::class,
            TaskSeeder::class,
        ]);
    }
}
