@extends('layouts.app')

@section('title', 'Panou de control')

@section('content')
    <div class="row">
        <div class="col-xl-3 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Compania mea</p>
                                <h5 class="font-weight-bolder">{{ $stats['company']->name }}</h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-primary shadow text-center rounded-circle">
                                <i class="fa-solid fa-building"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Conturi financiare</p>
                                <h5 class="font-weight-bolder">{{ $stats['accounts'] }}</h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-info shadow text-center rounded-circle">
                                <i class="fa-solid fa-coins"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Task-uri deschise</p>
                                <h5 class="font-weight-bolder">{{ $stats['open_tasks'] }}</h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-warning shadow text-center rounded-circle">
                                <i class="fa-solid fa-list-check"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Sold curent</p>
                                <h5 class="font-weight-bolder">{{ number_format($stats['balance'], 2, '.', ' ') }} RON</h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-success shadow text-center rounded-circle">
                                <i class="fa-solid fa-chart-line"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header pb-0">
                    <div class="row">
                        <div class="col-md-8">
                            <h6>Flux financiar (luna curentă)</h6>
                            <p class="text-sm mb-0">
                                <span class="font-weight-bold">Venituri:</span> {{ number_format($stats['revenue'], 2, '.', ' ') }} RON
                            </p>
                            <p class="text-sm mb-0">
                                <span class="font-weight-bold">Cheltuieli:</span> {{ number_format($stats['expenses'], 2, '.', ' ') }} RON
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <span class="badge bg-gradient-success">AI Insights</span>
                        </div>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div class="position-relative">
                        <canvas id="balanceChart" height="220"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header pb-0">
                    <h6>Task-uri recente</h6>
                </div>
                <div class="card-body p-3">
                    <ul class="list-group">
                        @forelse($recentTasks as $task)
                            <li class="list-group-item border-0 d-flex align-items-center justify-content-between mb-2 shadow-sm rounded-2">
                                <div>
                                    <h6 class="mb-0">{{ $task->title }}</h6>
                                    <p class="text-sm mb-0 text-muted">
                                        {{ optional($task->company)->name ?? 'Companie nedefinită' }} ·
                                        Status: <span class="badge bg-secondary text-uppercase">{{ $task->status }}</span>
                                    </p>
                                </div>
                                <div class="text-end">
                                    <span class="text-sm text-muted">Due: {{ optional($task->due_date)->format('d M') ?? 'N/A' }}</span>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item">Nu există task-uri înregistrate.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h6>Tranzacții recente</h6>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center mb-0">
                        <thead>
                        <tr>
                            <th>Companie</th>
                            <th>Descriere</th>
                            <th>Direcție</th>
                            <th>Suma</th>
                            <th>Data</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($recentTransactions as $transaction)
                            <tr>
                                <td>{{ optional($transaction->company)->name ?? 'N/A' }}</td>
                                <td>{{ $transaction->description ?? '-' }}</td>
                                <td>
                                    <span class="badge {{ $transaction->direction === 'credit' ? 'bg-gradient-success' : 'bg-gradient-danger' }}">
                                        {{ ucfirst($transaction->direction) }}
                                    </span>
                                </td>
                                <td>{{ number_format($transaction->amount, 2, '.', ' ') }} {{ $transaction->currency }}</td>
                                <td>{{ optional($transaction->occurred_at)->format('d M Y') ?? 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Nu există tranzacții înregistrate.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('argon/js/plugins/chartjs.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('balanceChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Venituri', 'Cheltuieli', 'Sold'],
                    datasets: [{
                        label: 'RON',
                        data: [
                            {{ $stats['revenue'] }},
                            {{ $stats['expenses'] }},
                            {{ $stats['balance'] }}
                        ],
                        backgroundColor: [
                            'rgba(66, 134, 244, 0.8)',
                            'rgba(234, 84, 85, 0.8)',
                            'rgba(45, 206, 137, 0.8)'
                        ],
                        borderRadius: 8,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: value => value + ' RON'
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush
