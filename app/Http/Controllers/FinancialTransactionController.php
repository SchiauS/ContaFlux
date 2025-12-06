<?php

namespace App\Http\Controllers;

use App\Models\FinancialAccount;
use App\Models\FinancialTransaction;
use Illuminate\Http\Request;

class FinancialTransactionController extends Controller
{
    public function index(Request $request)
    {
        $companyId = $request->user()->company_id;

        $transactions = FinancialTransaction::with(['account', 'company'])
            ->where('company_id', $companyId)
            ->orderByDesc('occurred_at')
            ->paginate()
            ->withQueryString();

        if ($request->wantsJson()) {
            return $transactions;
        }

        return view('transactions.index', [
            'transactions' => $transactions,
            'company' => \App\Models\Company::findOrFail($companyId),
            'accounts' => \App\Models\FinancialAccount::where('company_id', $companyId)->orderBy('code')->pluck('code', 'id'),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
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

        if (! FinancialAccount::where('company_id', $request->user()->company_id)->whereKey($data['financial_account_id'])->exists()) {
            abort(403, 'Contul selectat nu aparține companiei tale.');
        }

        $transaction = FinancialTransaction::create(array_merge($data, [
            'company_id' => $request->user()->company_id,
        ]));

        if ($request->wantsJson()) {
            return response()->json($transaction->load(['account', 'company']), 201);
        }

        return redirect()->route('transactions.index')->with('status', 'Tranzacția a fost înregistrată.');
    }

    public function show(FinancialTransaction $financialTransaction)
    {
//        $this->authorizeCompany($financialTransaction->company_id);
        return $financialTransaction->load(['account', 'company']);
    }

    public function update(Request $request, FinancialTransaction $financialTransaction)
    {
//        $this->authorizeCompany($financialTransaction->company_id);
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

        $financialTransaction->fill($data);

        if (! $financialTransaction->isDirty()) {
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Nu există modificări de salvat.',
                    'transaction' => $financialTransaction->load(['account', 'company']),
                ]);
            }

            return redirect()->route('transactions.index')->with('status', 'Nu au fost efectuate modificări.');
        }

        $financialTransaction->save();

        if ($request->wantsJson()) {
            return response()->json($financialTransaction->load(['account', 'company']));
        }

        return redirect()->route('transactions.index')->with('status', 'Tranzacția a fost actualizată.');
    }

    public function destroy(FinancialTransaction $financialTransaction)
    {
//        $this->authorizeCompany($financialTransaction->company_id);
        $financialTransaction->delete();

        if (request()->wantsJson()) {
            return response()->noContent();
        }

        return redirect()->route('transactions.index')->with('status', 'Tranzacția a fost ștearsă.');
    }

    private function authorizeCompany(?int $companyId): void
    {
        if ($companyId === null || $companyId !== auth()->user()->company_id) {
            abort(403, 'Nu ai acces la această tranzacție.');
        }
    }
}
