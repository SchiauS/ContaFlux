<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $companies = Company::withCount(['accounts', 'tasks'])
            ->latest()
            ->paginate()
            ->withQueryString();

        if ($request->wantsJson()) {
            return $companies;
        }

        return view('companies.index', ['companies' => $companies]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'fiscal_code' => 'nullable|string',
            'currency' => 'nullable|string|size:3',
            'fiscal_year_start' => 'nullable|date',
            'timezone' => 'nullable|string',
            'settings' => 'array',
        ]);

        $company = Company::create($data);

        if ($request->wantsJson()) {
            return response()->json($company, 201);
        }

        return redirect()->route('companies.index')->with('status', 'Compania a fost creată cu succes.');
    }

    public function show(Company $company)
    {
        return $company->load(['accounts', 'tasks']);
    }

    public function update(Request $request, Company $company)
    {
        $data = $request->validate([
            'name' => 'sometimes|string',
            'fiscal_code' => 'nullable|string',
            'currency' => 'nullable|string|size:3',
            'fiscal_year_start' => 'nullable|date',
            'timezone' => 'nullable|string',
            'settings' => 'array',
        ]);

        $company->update($data);

        if ($request->wantsJson()) {
            return response()->json($company);
        }

        return redirect()->route('companies.index')->with('status', 'Compania a fost actualizată.');
    }

    public function destroy(Company $company)
    {
        $company->delete();

        if (request()->wantsJson()) {
            return response()->noContent();
        }

        return redirect()->route('companies.index')->with('status', 'Compania a fost ștearsă.');
    }
}
