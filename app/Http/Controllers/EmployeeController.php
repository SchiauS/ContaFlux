<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeLeave;
use App\Models\EmployeePayment;
use App\Models\EmployeeTimeEntry;
use App\Models\FinancialAccount;
use App\Models\FinancialTransaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $companyId = $request->user()->company_id;

        $employees = Employee::with(['timeEntries' => function ($query) {
            $query->latest('worked_on')->limit(5);
        }, 'leaves' => function ($query) {
            $query->latest('start_date')->limit(5);
        }])
            ->where('company_id', $companyId)
            ->orderBy('name')
            ->get();

        $accounts = FinancialAccount::where('company_id', $companyId)->orderBy('name')->get();

        return view('employees.index', [
            'employees' => $employees,
            'accounts' => $accounts,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $companyId = $request->user()->company_id;

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'role' => ['nullable', 'string', 'max:255'],
            'salary' => ['required', 'numeric', 'min:0'],
        ]);

        Employee::create([
            'company_id' => $companyId,
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'role' => $validated['role'] ?? null,
            'salary' => $validated['salary'],
            'currency' => 'RON',
            'status' => 'active',
            'hired_at' => now()->toDateString(),
        ]);

        return back()->with('status', 'Angajat adăugat cu succes.');
    }

    public function update(Request $request, Employee $employee): RedirectResponse
    {
        $this->authorizeEmployee($request, $employee);

        $validated = $request->validate([
            'role' => ['nullable', 'string', 'max:255'],
            'salary' => ['required', 'numeric', 'min:0'],
            'email' => ['nullable', 'email', 'max:255'],
            'status' => ['required', Rule::in(['active', 'terminated'])],
        ]);

        $employee->update($validated);

        return back()->with('status', 'Datele angajatului au fost actualizate.');
    }

    public function terminate(Request $request, Employee $employee): RedirectResponse
    {
        $this->authorizeEmployee($request, $employee);

        $employee->update([
            'status' => 'terminated',
            'terminated_at' => now()->toDateString(),
        ]);

        return back()->with('status', 'Angajatul a fost concediat.');
    }

    public function reinstate(Request $request, Employee $employee): RedirectResponse
    {
        $this->authorizeEmployee($request, $employee);

        $employee->update([
            'status' => 'active',
            'terminated_at' => null,
        ]);

        return back()->with('status', 'Angajatul a fost reactivat.');
    }

    public function storeTimeEntry(Request $request, Employee $employee): RedirectResponse
    {
        $this->authorizeEmployee($request, $employee);

        $validated = $request->validate([
            'worked_on' => ['required', 'date'],
            'hours' => ['required', 'numeric', 'min:0', 'max:24'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        EmployeeTimeEntry::create([
            'employee_id' => $employee->id,
            'worked_on' => $validated['worked_on'],
            'hours' => $validated['hours'],
            'note' => $validated['note'] ?? null,
        ]);

        return back()->with('status', 'Pontajul a fost înregistrat.');
    }

    public function storeLeave(Request $request, Employee $employee): RedirectResponse
    {
        $this->authorizeEmployee($request, $employee);

        $validated = $request->validate([
            'type' => ['required', 'string', 'max:100'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'note' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', Rule::in(['approved', 'pending', 'rejected'])],
        ]);

        EmployeeLeave::create([
            'employee_id' => $employee->id,
            'type' => $validated['type'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'status' => $validated['status'] ?? 'approved',
            'note' => $validated['note'] ?? null,
        ]);

        return back()->with('status', 'Concediul a fost înregistrat.');
    }

    public function paySalary(Request $request, Employee $employee): RedirectResponse
    {
        $this->authorizeEmployee($request, $employee);

        $companyId = $request->user()->company_id;

        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:0'],
            'financial_account_id' => ['required', Rule::exists('financial_accounts', 'id')->where('company_id', $companyId)],
            'paid_at' => ['required', 'date'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        $transaction = FinancialTransaction::create([
            'company_id' => $companyId,
            'financial_account_id' => $validated['financial_account_id'],
            'counterparty' => $employee->name,
            'description' => $validated['note'] ?? 'Plată salariu',
            'direction' => 'debit',
            'amount' => $validated['amount'],
            'currency' => $employee->currency ?? 'RON',
            'tax_rate' => null,
            'occurred_at' => $validated['paid_at'],
            'metadata' => ['employee_id' => $employee->id],
        ]);

        EmployeePayment::create([
            'employee_id' => $employee->id,
            'amount' => $validated['amount'],
            'currency' => $employee->currency ?? 'RON',
            'paid_at' => $validated['paid_at'],
            'financial_transaction_id' => $transaction->id,
            'note' => $validated['note'] ?? 'Plată salariu',
        ]);

        return back()->with('status', 'Salariul a fost înregistrat ca tranzacție.');
    }

    protected function authorizeEmployee(Request $request, Employee $employee): void
    {
        abort_unless($employee->company_id === $request->user()->company_id, 403);
    }
}
