<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        return Company::paginate();
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

        return response()->json($company, 201);
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

        return response()->json($company);
    }

    public function destroy(Company $company)
    {
        $company->delete();

        return response()->noContent();
    }
}
