<?php

namespace App\Http\Controllers;

use App\Models\FinancialTransaction;
use Illuminate\Http\Request;

class FinancialTransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = FinancialTransaction::with(['account', 'company'])->orderByDesc('occurred_at');

        if ($request->filled('company_id')) {
            $query->where('company_id', $request->integer('company_id'));
        }

        if ($request->filled('account_id')) {
            $query->where('financial_account_id', $request->integer('account_id'));
        }

        $transactions = $query->paginate()->withQueryString();

        if ($request->wantsJson()) {
            return $transactions;
        }

        return view('transactions.index', [
            'transactions' => $transactions,
            'companies' => \App\Models\Company::orderBy('name')->pluck('name', 'id'),
            'accounts' => \App\Models\FinancialAccount::orderBy('code')->pluck('code', 'id'),
            'filters' => $request->only(['company_id', 'account_id']),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'company_id' => 'required|integer|exists:companies,id',
            'financial_account_id' => 'required|integer|exists:financial_accounts,id',
            'counterparty' => 'nullable|string',
            'description' => 'nullable|string',
            'direction' => 'required|in:debit,credit',
            'amount' => 'required|numeric',
            'currency' => 'nullable|string|size:3',
            'tax_rate' => 'nullable|numeric',
            'occurred_at' => 'required|date',
            'metadata' => 'array',
        ]);

        $transaction = FinancialTransaction::create($data);

        if ($request->wantsJson()) {
            return response()->json($transaction->load(['account', 'company']), 201);
        }

        return redirect()->route('transactions.index')->with('status', 'Tranzacția a fost înregistrată.');
    }

    public function show(FinancialTransaction $financialTransaction)
    {
        return $financialTransaction->load(['account', 'company']);
    }

    public function update(Request $request, FinancialTransaction $financialTransaction)
    {
        $data = $request->validate([
            'counterparty' => 'nullable|string',
            'description' => 'nullable|string',
            'direction' => 'in:debit,credit',
            'amount' => 'numeric',
            'currency' => 'nullable|string|size:3',
            'tax_rate' => 'nullable|numeric',
            'occurred_at' => 'date',
            'metadata' => 'array',
        ]);

        $financialTransaction->update($data);

        if ($request->wantsJson()) {
            return response()->json($financialTransaction->load(['account', 'company']));
        }

        return redirect()->route('transactions.index')->with('status', 'Tranzacția a fost actualizată.');
    }

    public function destroy(FinancialTransaction $financialTransaction)
    {
        $financialTransaction->delete();

        if (request()->wantsJson()) {
            return response()->noContent();
        }

        return redirect()->route('transactions.index')->with('status', 'Tranzacția a fost ștearsă.');
    }
}
