<?php

namespace App\Http\Controllers;

use App\Models\FinancialAccount;
use App\Models\FinancialTransaction;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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

    public function show(Request $request, FinancialTransaction $financialTransaction)
    {
        $this->assertSameCompany($request, $financialTransaction);

        return $financialTransaction->load(['account', 'company']);
    }

    public function update(Request $request, FinancialTransaction $financialTransaction)
    {
        $companyId = $request->user()->company_id;

        $this->assertSameCompany($request, $financialTransaction);

        $data = $request->validate([
            'counterparty' => 'nullable|string',
            'description' => 'nullable|string',
            'direction' => 'in:debit,credit',
            'amount' => 'numeric',
            'currency' => 'nullable|string|size:3',
            'tax_rate' => 'nullable|numeric',
            'occurred_at' => 'date',
            'metadata' => 'array',
            'financial_account_id' => [
                'sometimes',
                'required',
                'integer',
                Rule::exists('financial_accounts', 'id')->where('company_id', $companyId),
            ],
        ]);

        $currentAccount = $request->input('current_financial_account_id');
        if (array_key_exists('financial_account_id', $data)) {
            $data['financial_account_id'] = $data['financial_account_id'] ?? $currentAccount ?? $financialTransaction->financial_account_id;
        } elseif ($currentAccount) {
            $data['financial_account_id'] = $currentAccount;
        }

        $financialTransaction->fill($data);

        $financialTransaction->company_id = $companyId;

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
        $this->assertSameCompany(request(), $financialTransaction);

        $financialTransaction->delete();

        if (request()->wantsJson()) {
            return response()->noContent();
        }

        return redirect()->route('transactions.index')->with('status', 'Tranzacția a fost ștearsă.');
    }

    private function assertSameCompany(Request $request, FinancialTransaction $financialTransaction): void
    {
        abort_unless($financialTransaction->company_id === $request->user()->company_id, 403);
    }

}
