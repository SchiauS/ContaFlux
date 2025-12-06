<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Employee;
use App\Models\EmployeeLeave;
use App\Models\EmployeePayment;
use App\Models\EmployeeTimeEntry;
use App\Models\FinancialAccount;
use App\Models\FinancialTransaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class EmployeeSeeder extends Seeder
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

        $employees = [
            [
                'name' => 'Andrei Popescu',
                'email' => 'andrei.popescu@nebuladev.com',
                'role' => 'Senior Backend Developer',
                'salary' => 19500,
                'currency' => 'RON',
                'status' => 'active',
                'hired_at' => '2022-09-15',
            ],
            [
                'name' => 'Ioana Marinescu',
                'email' => 'ioana.marinescu@nebuladev.com',
                'role' => 'Frontend Engineer',
                'salary' => 16500,
                'currency' => 'RON',
                'status' => 'active',
                'hired_at' => '2023-02-01',
            ],
            [
                'name' => 'Mihai Ionescu',
                'email' => 'mihai.ionescu@nebuladev.com',
                'role' => 'DevOps Engineer',
                'salary' => 18000,
                'currency' => 'RON',
                'status' => 'active',
                'hired_at' => '2021-11-01',
            ],
            [
                'name' => 'Larisa Dumitru',
                'email' => 'larisa.dumitru@nebuladev.com',
                'role' => 'QA Specialist',
                'salary' => 12500,
                'currency' => 'RON',
                'status' => 'active',
                'hired_at' => '2024-05-20',
            ],
            [
                'name' => 'George Petrescu',
                'email' => 'george.petrescu@nebuladev.com',
                'role' => 'Product Manager',
                'salary' => 20000,
                'currency' => 'RON',
                'status' => 'active',
                'hired_at' => '2022-03-01',
            ],
        ];

        $employeeIds = collect($employees)->mapWithKeys(function (array $data) use ($company) {
            $employee = Employee::updateOrCreate(
                [
                    'company_id' => $company->id,
                    'email' => $data['email'],
                ],
                array_merge($data, ['company_id' => $company->id]),
            );

            return [$employee->email => $employee->id];
        });

        $this->seedLeaves($employeeIds);
        $this->seedTimeEntries($employeeIds);
        $this->seedPayments($employeeIds, $company);
    }

    private function seedLeaves($employeeIds): void
    {
        $leaves = [
            [
                'employee' => 'Andrei Popescu',
                'type' => 'Concediu de odihnă',
                'start_date' => '2025-07-08',
                'end_date' => '2025-07-12',
                'status' => 'approved',
                'note' => 'Vacanță de vară',
            ],
            [
                'employee' => 'Ioana Marinescu',
                'type' => 'Work from home',
                'start_date' => '2025-07-15',
                'end_date' => '2025-07-16',
                'status' => 'approved',
                'note' => 'Lucrează remote pentru sincronizare cu echipa din Cluj',
            ],
            [
                'employee' => 'Larisa Dumitru',
                'type' => 'Concediu medical',
                'start_date' => '2025-06-10',
                'end_date' => '2025-06-12',
                'status' => 'approved',
                'note' => 'Răceală sezonieră',
            ],
        ];

        foreach ($leaves as $leave) {
            $employeeId = $employeeIds[$this->getEmailFromName($leave['employee'])] ?? null;
            if (! $employeeId) {
                continue;
            }

            EmployeeLeave::updateOrCreate(
                [
                    'employee_id' => $employeeId,
                    'start_date' => $leave['start_date'],
                    'type' => $leave['type'],
                ],
                [
                    'end_date' => $leave['end_date'],
                    'status' => $leave['status'],
                    'note' => $leave['note'],
                ],
            );
        }
    }

    private function seedTimeEntries($employeeIds): void
    {
        $timeEntries = [
            [
                'employee' => 'Andrei Popescu',
                'worked_on' => '2025-07-01',
                'hours' => 7.5,
                'note' => 'Refactor API pentru billing și optimizări caching',
            ],
            [
                'employee' => 'Ioana Marinescu',
                'worked_on' => '2025-07-01',
                'hours' => 8,
                'note' => 'Implementare UI pentru dashboardul de project tracking',
            ],
            [
                'employee' => 'Mihai Ionescu',
                'worked_on' => '2025-07-02',
                'hours' => 7,
                'note' => 'Pipeline CI/CD pentru microserviciul de autentificare',
            ],
            [
                'employee' => 'Larisa Dumitru',
                'worked_on' => '2025-07-02',
                'hours' => 6.5,
                'note' => 'Testare regresie pe release-ul mobil',
            ],
            [
                'employee' => 'George Petrescu',
                'worked_on' => '2025-07-03',
                'hours' => 7,
                'note' => 'Grooming backlog și discuții cu stakeholderii',
            ],
        ];

        foreach ($timeEntries as $entry) {
            $employeeId = $employeeIds[$this->getEmailFromName($entry['employee'])] ?? null;
            if (! $employeeId) {
                continue;
            }

            EmployeeTimeEntry::updateOrCreate(
                [
                    'employee_id' => $employeeId,
                    'worked_on' => $entry['worked_on'],
                ],
                [
                    'hours' => $entry['hours'],
                    'note' => $entry['note'],
                ],
            );
        }
    }

    private function seedPayments($employeeIds, Company $company): void
    {
        $payrollAccountId = FinancialAccount::where('company_id', $company->id)
            ->where('code', '421')
            ->value('id') ?? FinancialAccount::where('company_id', $company->id)->value('id');

        $payments = [
            [
                'employee' => 'Andrei Popescu',
                'amount' => 19500,
                'paid_at' => Carbon::parse('2025-06-30 17:15:00'),
                'note' => 'Salariu iunie 2025',
            ],
            [
                'employee' => 'Ioana Marinescu',
                'amount' => 16500,
                'paid_at' => Carbon::parse('2025-06-30 17:20:00'),
                'note' => 'Salariu iunie 2025',
            ],
            [
                'employee' => 'Mihai Ionescu',
                'amount' => 18000,
                'paid_at' => Carbon::parse('2025-06-30 17:05:00'),
                'note' => 'Salariu iunie 2025',
            ],
            [
                'employee' => 'Larisa Dumitru',
                'amount' => 12500,
                'paid_at' => Carbon::parse('2025-06-30 17:25:00'),
                'note' => 'Salariu iunie 2025',
            ],
            [
                'employee' => 'George Petrescu',
                'amount' => 20000,
                'paid_at' => Carbon::parse('2025-06-30 17:00:00'),
                'note' => 'Salariu iunie 2025',
            ],
        ];

        foreach ($payments as $payment) {
            $employeeId = $employeeIds[$this->getEmailFromName($payment['employee'])] ?? null;
            if (! $employeeId) {
                continue;
            }

            $transaction = FinancialTransaction::updateOrCreate(
                [
                    'company_id' => $company->id,
                    'financial_account_id' => $payrollAccountId,
                    'occurred_at' => $payment['paid_at'],
                    'counterparty' => $payment['employee'],
                ],
                [
                    'description' => $payment['note'],
                    'direction' => 'debit',
                    'amount' => $payment['amount'],
                    'currency' => 'RON',
                    'tax_rate' => null,
                    'metadata' => ['employee_id' => $employeeId],
                ],
            );

            EmployeePayment::updateOrCreate(
                [
                    'employee_id' => $employeeId,
                    'paid_at' => $payment['paid_at'],
                ],
                [
                    'amount' => $payment['amount'],
                    'currency' => 'RON',
                    'note' => $payment['note'],
                    'financial_transaction_id' => $transaction?->id,
                ],
            );
        }
    }

    private function getEmailFromName(string $name): string
    {
        return match ($name) {
            'Andrei Popescu' => 'andrei.popescu@nebuladev.com',
            'Ioana Marinescu' => 'ioana.marinescu@nebuladev.com',
            'Mihai Ionescu' => 'mihai.ionescu@nebuladev.com',
            'Larisa Dumitru' => 'larisa.dumitru@nebuladev.com',
            'George Petrescu' => 'george.petrescu@nebuladev.com',
            default => '',
        };
    }
}
