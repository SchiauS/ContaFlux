<?php

namespace App\Http\Controllers;

use App\Models\FinancialAccount;
use Illuminate\Http\Request;

class FinancialAccountController extends Controller
{
    public function index(Request $request)
    {
        $query = FinancialAccount::query();

        if ($request->filled('company_id')) {
            $query->where('company_id', $request->integer('company_id'));
        }

        return $query->paginate();
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

        return response()->json($account, 201);
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

        return response()->json($financialAccount);
    }

    public function destroy(FinancialAccount $financialAccount)
    {
        $financialAccount->delete();

        return response()->noContent();
    }
}
