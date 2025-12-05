<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\FinancialAccount;
use Illuminate\Http\Request;

class FinancialAccountController extends Controller
{
    public function index(Request $request)
    {
        $companyId = $request->user()->company_id;

        $accounts = FinancialAccount::with('company')
            ->where('company_id', $companyId)
            ->paginate()
            ->withQueryString();

        if ($request->wantsJson()) {
            return $accounts;
        }

        return view('accounts.index', [
            'accounts' => $accounts,
            'company' => Company::findOrFail($companyId),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string',
            'name' => 'required|string',
            'type' => 'nullable|string',
            'category' => 'nullable|string',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $account = FinancialAccount::create(array_merge($data, [
            'company_id' => $request->user()->company_id,
        ]));

        if ($request->wantsJson()) {
            return response()->json($account, 201);
        }

        return redirect()->route('accounts.index')->with('status', 'Contul a fost creat.');
    }

    public function show(FinancialAccount $financialAccount)
    {
        $this->authorizeCompany($financialAccount->company_id);
        return $financialAccount->load('transactions');
    }

    public function update(Request $request, FinancialAccount $financialAccount)
    {
        $this->authorizeCompany($financialAccount->company_id);
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
        $this->authorizeCompany($financialAccount->company_id);
        $financialAccount->delete();

        if (request()->wantsJson()) {
            return response()->noContent();
        }

        return redirect()->route('accounts.index')->with('status', 'Contul a fost șters.');
    }

    private function authorizeCompany(int $companyId): void
    {
        if ($companyId !== auth()->user()->company_id) {
            abort(403, 'Nu ai acces la acest cont.');
        }
    }
}
