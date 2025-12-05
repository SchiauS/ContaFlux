@extends('layouts.app')

@section('title', 'Conturi financiare')

@section('content')
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card shadow-sm">
                <div class="card-header bg-gradient-primary text-white py-3 d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                    <div>
                        <h5 class="mb-0 text-white">Conturi financiare</h5>
                        <span class="text-sm">Monitorizează planul de conturi și soldurile asociate.</span>
                    </div>
                    <form class="d-flex gap-2 mt-3 mt-md-0" method="GET">
                        <select name="company_id" class="form-select">
                            <option value="">Toate companiile</option>
                            @foreach($companies as $id => $name)
                                <option value="{{ $id }}" @selected(($filters['company_id'] ?? '') == $id)>{{ $name }}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-light" type="submit">
                            <i class="fa-solid fa-filter"></i> Filtrează
                        </button>
                    </form>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead>
                            <tr>
                                <th>Cod</th>
                                <th>Nume cont</th>
                                <th>Companie</th>
                                <th>Tip</th>
                                <th>Categorie</th>
                                <th>Activ</th>
                                <th>Creat la</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($accounts as $account)
                                <tr>
                                    <td class="text-sm fw-bold">{{ $account->code }}</td>
                                    <td>
                                        <div class="fw-semibold">{{ $account->name }}</div>
                                        <div class="text-muted text-sm">{{ $account->description ?? '—' }}</div>
                                    </td>
                                    <td class="text-sm">{{ optional($account->company)->name ?? '—' }}</td>
                                    <td class="text-sm text-uppercase">{{ $account->type ?? 'N/A' }}</td>
                                    <td class="text-sm">{{ $account->category ?? '—' }}</td>
                                    <td>
                                        <span class="badge {{ $account->is_active ? 'bg-gradient-success' : 'bg-gradient-secondary' }}">
                                            {{ $account->is_active ? 'Activ' : 'Inactiv' }}
                                        </span>
                                    </td>
                                    <td class="text-sm text-muted">{{ optional($account->created_at)->format('d M Y') ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <i class="fa-solid fa-circle-info mb-2"></i>
                                        <p class="mb-0">Nu există conturi definite în criteriile selectate.</p>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($accounts->hasPages())
                    <div class="card-footer">
                        {{ $accounts->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
