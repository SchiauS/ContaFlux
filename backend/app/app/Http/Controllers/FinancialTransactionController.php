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

        return $query->paginate();
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

        return response()->json($transaction->load(['account', 'company']), 201);
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

        return response()->json($financialTransaction->load(['account', 'company']));
    }

    public function destroy(FinancialTransaction $financialTransaction)
    {
        $financialTransaction->delete();

        return response()->noContent();
    }
}
