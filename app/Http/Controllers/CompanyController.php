<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $company = $request->user()->company()->withCount(['accounts', 'tasks'])->firstOrFail();

        return view('companies.index', ['company' => $company]);
    }

    public function show(Company $company)
    {
        return $company->load(['accounts', 'tasks']);
    }

    public function update(Request $request, Company $company)
    {
        if ($company->id !== $request->user()->company_id) {
            abort(403, 'Nu ai acces la această companie.');
        }

        $data = $request->validate([
            'name' => 'sometimes|string',
            'fiscal_code' => 'nullable|string',
            'currency' => 'nullable|string|size:3',
            'fiscal_year_start' => 'nullable|date',
            'timezone' => 'nullable|string',
            'settings' => 'array',
            'settings.working_hours' => 'nullable|string',
            'settings.positions' => 'nullable',
        ]);

        if (! empty($data['settings']['positions']) && is_string($data['settings']['positions'])) {
            $data['settings']['positions'] = array_filter(array_map('trim', explode(',', $data['settings']['positions'])));
        }

        if (isset($data['settings'])) {
            $data['settings'] = array_merge($company->settings ?? [], $data['settings']);
        }

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
