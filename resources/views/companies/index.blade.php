@extends('layouts.app')

@section('title', 'Companii')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                    <div>
                        <h5 class="mb-0">Companii</h5>
                        <span class="text-sm text-muted">Organizații monitorizate în platformă.</span>
                    </div>
                    <a href="#" class="btn btn-primary mt-3 mt-md-0 disabled">
                        <i class="fa-solid fa-plus"></i> Adaugă companie
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center mb-0">
                        <thead>
                        <tr>
                            <th>Nume</th>
                            <th>CUI</th>
                            <th>Monedă</th>
                            <th>Conturi</th>
                            <th>Task-uri</th>
                            <th>Creată</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($companies as $company)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $company->name }}</div>
                                    <div class="text-muted text-sm">{{ $company->timezone ?? 'N/A' }}</div>
                                </td>
                                <td class="text-sm">{{ $company->fiscal_code ?? '—' }}</td>
                                <td class="text-sm">{{ $company->currency ?? 'RON' }}</td>
                                <td class="text-sm">{{ $company->accounts_count }}</td>
                                <td class="text-sm">{{ $company->tasks_count }}</td>
                                <td class="text-sm text-muted">{{ optional($company->created_at)->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <p class="mb-0">Nu există companii înregistrate.</p>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                @if($companies->hasPages())
                    <div class="card-footer">
                        {{ $companies->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
