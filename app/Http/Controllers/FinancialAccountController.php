<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\FinancialAccount;
use Illuminate\Http\Request;

class FinancialAccountController extends Controller
{
    public function index(Request $request)
    {
        $query = FinancialAccount::with('company');

        if ($request->filled('company_id')) {
            $query->where('company_id', $request->integer('company_id'));
        }

        $accounts = $query->paginate()->withQueryString();

        if ($request->wantsJson()) {
            return $accounts;
        }

        $companies = Company::orderBy('name')->pluck('name', 'id');

        return view('accounts.index', [
            'accounts' => $accounts,
            'companies' => $companies,
            'filters' => $request->only(['company_id']),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'company_id' => 'required|integer|exists:companies,id',
            'code' => 'required|string',
            'name' => 'required|string',
            'type' => 'nullable|string',
            'category' => 'nullable|string',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $account = FinancialAccount::create($data);

        if ($request->wantsJson()) {
            return response()->json($account, 201);
        }

        return redirect()->route('accounts.index')->with('status', 'Contul a fost creat.');
    }

    public function show(FinancialAccount $financialAccount)
    {
        return $financialAccount->load('transactions');
    }

    public function update(Request $request, FinancialAccount $financialAccount)
    {
        $data = $request->validate([
            'code' => 'sometimes|string',
            'name' => 'sometimes|string',
            'type' => 'nullable|string',
            'category' => 'nullable|string',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $financialAccount->update($data);

        if ($request->wantsJson()) {
            return response()->json($financialAccount);
        }

        return redirect()->route('accounts.index')->with('status', 'Contul a fost actualizat.');
    }

    public function destroy(FinancialAccount $financialAccount)
    {
        $financialAccount->delete();

        if (request()->wantsJson()) {
            return response()->noContent();
        }

        return redirect()->route('accounts.index')->with('status', 'Contul a fost șters.');
    }
}
