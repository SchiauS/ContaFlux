@extends('layouts.app')

@section('title', 'Rapoarte financiare')

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h6 class="mb-0">Rapoarte dinamice</h6>
                        <p class="text-sm text-muted mb-0">Selectează perioada pentru a genera graficele.</p>
                    </div>
                    <form class="d-flex align-items-center gap-2" method="GET" action="{{ route('reports.index') }}">
                        <div>
                            <label class="form-label text-xs mb-1">Perioadă cazare</label>
                            <input
                                type="text"
                                id="date_range"
                                class="form-control date-filter"
                                placeholder="Selectează perioada"
                                autocomplete="off"
                                value="<?= (!empty($startDate) && !empty($endDate))
                            ? date('d.m.Y', strtotime($startDate)) . ' - ' . date('d.m.Y', strtotime($endDate))
                            : '' ?>">

                            <input type="hidden" name="start_date" id="start_date" value="<?= $startDate ?? '' ?>">
                            <input type="hidden" name="end_date" id="end_date" value="<?= $endDate?? '' ?>">
                        </div>
                        <div class="align-self-end">
                            <button type="submit" class="btn btn-sm btn-primary mb-0"><i class="fa-solid fa-rotate"></i> Actualizează</button>
                        </div>
                    </form>
                </div>
                <div class="card-body py-3">
                    <div class="row text-center">
                        <div class="col-md-3 col-6 mb-3">
                            <p class="text-sm text-muted mb-1">Venituri</p>
                            <h5 class="mb-0 text-success">{{ number_format($summary['revenue'], 2, '.', ' ') }} RON</h5>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <p class="text-sm text-muted mb-1">Cheltuieli</p>
                            <h5 class="mb-0 text-danger">{{ number_format($summary['expenses'], 2, '.', ' ') }} RON</h5>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <p class="text-sm text-muted mb-1">Sold</p>
                            <h5 class="mb-0">{{ number_format($summary['balance'], 2, '.', ' ') }} RON</h5>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <p class="text-sm text-muted mb-1">Tranzacții</p>
                            <h5 class="mb-0">{{ $summary['count'] }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-7">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex align-items-center justify-content-between">
                    <h6 class="mb-0">Evoluție zilnică</h6>
                    <span class="badge bg-gradient-success">Linie</span>
                </div>
                <div class="card-body">
                    <canvas id="trendChart" height="320"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-5">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex align-items-center justify-content-between">
                    <h6 class="mb-0">Venituri vs Cheltuieli</h6>
                    <span class="badge bg-gradient-info">Bară</span>
                </div>
                <div class="card-body">
                    <canvas id="balanceStackedChart" height="320"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex align-items-center justify-content-between">
                    <h6 class="mb-0">Repartizare pe conturi</h6>
                    <span class="badge bg-gradient-primary">Donut</span>
                </div>
                <div class="card-body">
                    <canvas id="accountChart" height="320"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card mb-4 h-100">
                <div class="card-header pb-0 d-flex align-items-center justify-content-between">
                    <h6 class="mb-0">Top parteneri</h6>
                    <span class="badge bg-gradient-warning text-dark">Clasament</span>
                </div>
                <div class="card-body">
                    <canvas id="counterpartyChart" height="320"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('argon/js/plugins/chartjs.min.js') }}"></script>
    <script>
        const trendData = @json($trend);
        const distribution = @json($distribution);
        const accountLabels = @json($accountLabels);
        const byAccount = @json($byAccount);
        const counterparties = @json($counterparties);

        const palette = ['#4c51bf', '#2dce89', '#e14eca', '#5e72e4', '#11cdef', '#f5365c', '#fb6340', '#ffd600'];

        const ctxDistribution = document.getElementById('distributionChart');
        new Chart(ctxDistribution, {
            type: 'doughnut',
            data: {
                labels: distribution.labels,
                datasets: [{
                    data: distribution.values,
                    backgroundColor: ['#2dce89', '#f5365c']
                }]
            },
            options: {
                plugins: { legend: { position: 'bottom' } },
                cutout: '65%'
            }
        });

        const ctxTrend = document.getElementById('trendChart');
        new Chart(ctxTrend, {
            type: 'line',
            data: {
                labels: trendData.map(item => item.date),
                datasets: [
                    {
                        label: 'Venituri',
                        data: trendData.map(item => item.revenue),
                        borderColor: '#2dce89',
                        backgroundColor: 'rgba(45, 206, 137, 0.08)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Cheltuieli',
                        data: trendData.map(item => item.expenses),
                        borderColor: '#f5365c',
                        backgroundColor: 'rgba(245, 54, 92, 0.08)',
                        tension: 0.4,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                interaction: { mode: 'index', intersect: false },
                stacked: false,
                plugins: {
                    legend: { position: 'top' }
                }
            }
        });

        const ctxBalance = document.getElementById('balanceStackedChart');
        new Chart(ctxBalance, {
            type: 'bar',
            data: {
                labels: ['Total'],
                datasets: [
                    {
                        label: 'Venituri',
                        data: [distribution.values[0]],
                        backgroundColor: '#2dce89'
                    },
                    {
                        label: 'Cheltuieli',
                        data: [distribution.values[1]],
                        backgroundColor: '#f5365c'
                    },
                    {
                        label: 'Sold',
                        data: [distribution.values[0] - distribution.values[1]],
                        backgroundColor: '#5e72e4'
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'bottom' } },
                scales: {
                    x: { stacked: true },
                    y: { stacked: true }
                }
            }
        });

        const ctxAccount = document.getElementById('accountChart');
        new Chart(ctxAccount, {
            type: 'doughnut',
            data: {
                labels: byAccount.map(item => accountLabels[item.account_id] ?? 'Cont nedefinit'),
                datasets: [{
                    data: byAccount.map(item => item.amount),
                    backgroundColor: byAccount.map((_, idx) => palette[idx % palette.length])
                }]
            },
            options: {
                plugins: { legend: { position: 'bottom' } },
                cutout: '55%'
            }
        });

        const ctxCounterparty = document.getElementById('counterpartyChart');
        new Chart(ctxCounterparty, {
            type: 'bar',
            data: {
                labels: counterparties.map(item => item.name),
                datasets: [{
                    label: 'Total tranzacții',
                    data: counterparties.map(item => item.total),
                    backgroundColor: '#11cdef',
                    borderRadius: 8
                }]
            },
            options: {
                indexAxis: 'y',
                plugins: { legend: { display: false } },
                scales: {
                    x: { beginAtZero: true }
                }
            }
        });
        // === DATE RANGE PICKER ===
        const filterDate = {
            showDropdowns: true,
            autoUpdateInput: false,
            minYear: 2018,
            maxYear: <?= (int)date("Y")+5 ?>,
            minDate: moment().add(1, 'days'),
            locale: {
                format: 'YYYY-MM-DD',
                applyLabel: "Aplică",
                cancelLabel: "Șterge",
                customRangeLabel: "Selectează intervalul",
                daysOfWeek: ['Du', 'Lu', 'Ma', 'Mi', 'Jo', 'Vi', 'Sâ'],
                monthNames: ['Ianuarie', 'Februarie', 'Martie', 'Aprilie', 'Mai', 'Iunie', 'Iulie', 'August', 'Septembrie', 'Octombrie', 'Noiembrie', 'Decembrie'],
                firstDay: 1
            },
            ranges: {
                'Weekend-ul viitor': [moment().add(5, 'days'), moment().add(7, 'days')],
                'Săptămâna aceasta': [moment().startOf('week').add(1, 'day'), moment().endOf('week').add(1, 'day')],
                'Luna aceasta': [moment().startOf('month'), moment().endOf('month')],
                'Luna viitoare': [moment().add(1, 'month').startOf('month'), moment().add(1, 'month').endOf('month')]
            }
        };

        $('#date_range').daterangepicker(filterDate);

        $('#date_range').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD') + ' → ' + picker.endDate.format('YYYY-MM-DD'));
            $('#start_date').val(picker.startDate.format('YYYY-MM-DD'));
            $('#end_date').val(picker.endDate.format('YYYY-MM-DD'));
            $('#error_daterange').addClass('d-none');
        });

        $('#date_range').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
            $('#start_date, #end_date').val('');
        });

    </script>
@endpush
