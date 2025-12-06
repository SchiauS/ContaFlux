<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::firstOrCreate(
            ['name' => 'Nebula Dev Studio SRL'],
            [
                'fiscal_code' => 'RO98765432',
                'currency' => 'RON',
                'fiscal_year_start' => '2025-01-01',
                'timezone' => 'Europe/Bucharest',
            ],
        );

        $user = User::firstOrCreate(
            ['email' => 'schiau.m.sebastianadrian25@stud.rau.ro'],
            [
                'name' => 'Schiau Sebastian-Adrian',
                'password' => bcrypt('password'),
                'company_id' => $company->id,
            ],
        );

        $tasks = [
            [
                'title' => 'Reconciliază extrasele bancare',
                'description' => 'Verifică tranzacțiile din ianuarie și potrivește-le cu facturile.',
                'status' => 'open',
                'due_date' => '2025-02-05',
                'priority' => 'high',
            ],
            [
                'title' => 'Emite facturi recurente',
                'description' => 'Generează facturile lunare pentru clienții enterprise.',
                'status' => 'in_progress',
                'due_date' => '2025-02-10',
                'priority' => 'normal',
            ],
            [
                'title' => 'Pregătește raportul de TVA',
                'description' => 'Centralizează veniturile și cheltuielile pentru declarația de TVA.',
                'status' => 'open',
                'due_date' => '2025-02-15',
                'priority' => 'high',
            ],
            [
                'title' => 'Urmărește restanțele clienților',
                'description' => 'Contactează clienții cu facturi scadente și propune planuri de plată.',
                'status' => 'done',
                'due_date' => '2025-01-31',
                'priority' => 'low',
            ],
        ];

        foreach ($tasks as $task) {
            Task::updateOrCreate(
                [
                    'company_id' => $company->id,
                    'title' => $task['title'],
                ],
                array_merge($task, [
                    'company_id' => $company->id,
                    'user_id' => $user->id,
                ]),
            );
        }
    }
}
