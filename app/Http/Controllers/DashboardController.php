<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\FinancialAccount;
use App\Models\FinancialTransaction;
use App\Models\Task;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $companyId = auth()->user()->company_id;
        $company = Company::findOrFail($companyId);

        $stats = [
            'company' => $company,
            'accounts' => $company->accounts()->count(),
            'open_tasks' => $company->tasks()->where('status', '!=', 'done')->count(),
        ];

        $periodQuery = FinancialTransaction::query()
            ->where('company_id', $companyId)
            ->whereBetween('occurred_at', [now()->startOfMonth(), now()->endOfMonth()]);

        $stats['revenue'] = (clone $periodQuery)->where('direction', 'credit')->sum('amount');
        $stats['expenses'] = (clone $periodQuery)->where('direction', 'debit')->sum('amount');
        $stats['balance'] = $stats['revenue'] - $stats['expenses'];

        $recentTransactions = FinancialTransaction::with('company')
            ->where('company_id', $companyId)
            ->latest('occurred_at')
            ->take(5)
            ->get();

        $recentTasks = Task::with('company')
            ->where('company_id', $companyId)
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.index', [
            'stats' => $stats,
            'recentTransactions' => $recentTransactions,
            'recentTasks' => $recentTasks,
        ]);
    }

    public function summary(Request $request)
    {
        $companyId = $request->user()->company_id;
        $period = $request->query('period', 'month');

        $query = FinancialTransaction::query()->where('company_id', $companyId);

        $start = match ($period) {
            'week' => now()->startOfWeek(),
            'quarter' => now()->firstOfQuarter(),
            'year' => now()->startOfYear(),
            default => now()->startOfMonth(),
        };

        $query->where('occurred_at', '>=', $start);

        $totals = [
            'debit' => (clone $query)->where('direction', 'debit')->sum('amount'),
            'credit' => (clone $query)->where('direction', 'credit')->sum('amount'),
        ];

        $openTasks = Task::query()
            ->when($companyId, fn ($q) => $q->where('company_id', $companyId))
            ->where('status', '!=', 'done')
            ->count();

        return [
            'period_start' => $start->toDateString(),
            'totals' => $totals,
            'balance' => $totals['credit'] - $totals['debit'],
            'open_tasks' => $openTasks,
        ];
    }
}
