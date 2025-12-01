<?php

namespace App\Http\Controllers;

use App\Models\FinancialTransaction;
use App\Models\Task;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function summary(Request $request)
    {
        $companyId = $request->query('company_id');
        $period = $request->query('period', 'month');

        $query = FinancialTransaction::query();

        if ($companyId) {
            $query->where('company_id', $companyId);
        }

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
