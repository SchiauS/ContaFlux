@extends('layouts.app')

@section('title', 'Global View - Rapoarte')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h6 class="mb-0">Rapoarte</h6>
                        <p class="text-sm text-muted mb-0">Acces rapid la rapoarte agregate pentru întreaga platformă.</p>
                    </div>
                    <span class="badge bg-gradient-primary">Global</span>
                </div>
                <div class="card-body">
                    <p class="text-sm mb-4">
                        Consultă rezultate centralizate pentru licitații, recomandări și alți indicatori relevanți. Secțiunea va fi
                        punctul unic pentru raportările de nivel executiv.
                    </p>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="border rounded-3 p-3 h-100">
                                <h6 class="mb-2"><i class="fa-solid fa-chart-column text-primary me-2"></i>Licitații totale</h6>
                                <p class="text-sm text-muted mb-0">Total licitații efectuate și evoluția lor în timp.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded-3 p-3 h-100">
                                <h6 class="mb-2"><i class="fa-solid fa-bullhorn text-primary me-2"></i>Recomandări</h6>
                                <p class="text-sm text-muted mb-0">Top recomandări și recomandatorii cei mai activi.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded-3 p-3 h-100">
                                <h6 class="mb-2"><i class="fa-solid fa-gauge-high text-primary me-2"></i>Indicatori cheie</h6>
                                <p class="text-sm text-muted mb-0">Acces la KPI esențiali pentru starea generală a platformei.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
