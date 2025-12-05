@extends('layouts.app')

@section('title', 'Conturi financiare')

@section('content')
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card shadow-sm">
                <div class="card-header bg-gradient-primary text-white py-3 d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                    <div>
                        <h5 class="mb-0 text-white">Conturi financiare</h5>
                        <span class="text-sm">Companie: {{ $company->name }}</span>
                    </div>
                    <button class="btn btn-dark mt-3 mt-md-0" type="button" data-bs-toggle="collapse" data-bs-target="#createAccountForm">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                </div>
                <div id="createAccountForm" class="collapse border-bottom">
                    <form class="p-4" method="POST" action="{{ route('accounts.store') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label text-sm">Cod</label>
                                <input type="text" class="form-control" name="code" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-sm">Nume</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-sm">Tip</label>
                                <input type="text" class="form-control" name="type">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-sm">Categorie</label>
                                <input type="text" class="form-control" name="category">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-sm">Activ</label>
                                <select name="is_active" class="form-select">
                                    <option value="1">Da</option>
                                    <option value="0">Nu</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label text-sm">Descriere</label>
                                <textarea class="form-control" name="description" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="mt-3 d-flex justify-content-end">
                            <button class="btn btn-success" type="submit">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Salvează
                            </button>
                        </div>
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
