<?php

namespace App\Http\Controllers;

use App\Models\FinancialAccount;
use App\Models\FinancialTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        $companyId = $request->user()->company_id;

        $start = $request->input('start_date');
        $end = $request->input('end_date');

        $startDate = $start ? Carbon::parse($start)->startOfDay() : now()->subDays(90)->startOfDay();
        $endDate = $end ? Carbon::parse($end)->endOfDay() : now()->endOfDay();

        if ($startDate->greaterThan($endDate)) {
            [$startDate, $endDate] = [$endDate->copy()->startOfDay(), $startDate->copy()->endOfDay()];
        }

        $transactions = FinancialTransaction::query()
            ->where('company_id', $companyId)
            ->whereBetween('occurred_at', [$startDate, $endDate])
            ->orderBy('occurred_at')
            ->get();

        $summary = [
            'revenue' => $transactions->where('direction', 'credit')->sum('amount'),
            'expenses' => $transactions->where('direction', 'debit')->sum('amount'),
            'count' => $transactions->count(),
        ];
        $summary['balance'] = $summary['revenue'] - $summary['expenses'];

        $trend = $transactions
            ->groupBy(fn ($tx) => Carbon::parse($tx->occurred_at)->toDateString())
            ->map(function (Collection $items, $date) {
                return [
                    'date' => $date,
                    'revenue' => $items->where('direction', 'credit')->sum('amount'),
                    'expenses' => $items->where('direction', 'debit')->sum('amount'),
                ];
            })
            ->values();

        $byAccount = $transactions
            ->groupBy('financial_account_id')
            ->map(fn ($items, $accountId) => [
                'account_id' => $accountId,
                'amount' => $items->sum('amount'),
                'direction' => $items->first()?->direction ?? 'debit',
            ])
            ->values();

        $accountLabels = FinancialAccount::where('company_id', $companyId)
            ->pluck('name', 'id');

        $counterparties = $transactions
            ->filter(fn ($tx) => filled($tx->counterparty))
            ->groupBy('counterparty')
            ->map(fn ($items, $name) => [
                'name' => $name,
                'total' => $items->sum('amount'),
            ])
            ->sortByDesc('total')
            ->take(8)
            ->values();

        $distribution = [
            'labels' => ['Venituri', 'Cheltuieli'],
            'values' => [
                $summary['revenue'],
                $summary['expenses'],
            ],
        ];

        return view('reports.index', [
            'summary' => $summary,
            'trend' => $trend,
            'distribution' => $distribution,
            'byAccount' => $byAccount,
            'accountLabels' => $accountLabels,
            'counterparties' => $counterparties,
            'startDate' => $startDate->toDateString(),
            'endDate' => $endDate->toDateString(),
        ]);
    }
}
