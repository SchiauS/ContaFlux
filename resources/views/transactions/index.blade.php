@extends('layouts.app')

@section('title', 'Tranzacții')

@section('content')
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                    <h5 class="mb-0">Tranzacții financiare</h5>
                    <div class="d-flex gap-2 mt-3 mt-md-0">
                        <span class="text-sm text-muted d-inline-block me-3">Ultimele înregistrări contabile.</span>
                        <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#createTransactionForm">
                            <i class="fa-solid fa-plus me-1"></i> Adaugă
                        </button>
                    </div>
                </div>
                <div id="createTransactionForm" class="collapse border-bottom">
                    <form class="p-4" method="POST" action="{{ route('transactions.store') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label text-sm">Companie</label>
                                <select name="company_id" class="form-select" required>
                                    <option value="">Selectează</option>
                                    @foreach($companies as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-sm">Cont</label>
                                <select name="financial_account_id" class="form-select" required>
                                    <option value="">Selectează</option>
                                    @foreach($accounts as $id => $code)
                                        <option value="{{ $id }}">{{ $code }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-sm">Direcție</label>
                                <select name="direction" class="form-select" required>
                                    <option value="credit">Credit</option>
                                    <option value="debit">Debit</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-sm">Sumă</label>
                                <input type="number" step="0.01" class="form-control" name="amount" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-sm">Monedă</label>
                                <input type="text" class="form-control" name="currency" placeholder="RON">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-sm">Data</label>
                                <input type="date" class="form-control" name="occurred_at" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-sm">Partener</label>
                                <input type="text" class="form-control" name="counterparty">
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
                <div class="card-body border-bottom">
                    <form class="row g-3" method="GET">
                        <div class="col-md-4">
                            <label class="form-label text-sm">Companie</label>
                            <select name="company_id" class="form-select">
                                <option value="">Toate</option>
                                @foreach($companies as $id => $name)
                                    <option value="{{ $id }}" @selected(($filters['company_id'] ?? '') == $id)>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-sm">Cont</label>
                            <select name="account_id" class="form-select">
                                <option value="">Toate</option>
                                @foreach($accounts as $id => $code)
                                    <option value="{{ $id }}" @selected(($filters['account_id'] ?? '') == $id)>{{ $code }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button class="btn btn-primary w-100" type="submit">
                                <i class="fa-solid fa-filter"></i> Aplică filtre
                            </button>
                        </div>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center mb-0">
                        <thead>
                        <tr>
                            <th>Descriere</th>
                            <th>Companie</th>
                            <th>Cont</th>
                            <th>Direcție</th>
                            <th>Suma</th>
                            <th>Data</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($transactions as $transaction)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $transaction->description ?? '—' }}</div>
                                    <div class="text-muted text-sm">{{ $transaction->counterparty ?? 'N/A' }}</div>
                                </td>
                                <td class="text-sm">{{ optional($transaction->company)->name ?? '—' }}</td>
                                <td class="text-sm">{{ optional($transaction->account)->code ?? '—' }}</td>
                                <td>
                                    <span class="badge {{ $transaction->direction === 'credit' ? 'bg-gradient-success' : 'bg-gradient-danger' }}">
                                        {{ ucfirst($transaction->direction) }}
                                    </span>
                                </td>
                                <td class="text-sm fw-bold">{{ number_format($transaction->amount, 2, '.', ' ') }} {{ $transaction->currency }}</td>
                                <td class="text-sm text-muted">{{ optional($transaction->occurred_at)->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    Nu există tranzacții pentru filtrele selectate.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                @if($transactions->hasPages())
                    <div class="card-footer">
                        {{ $transactions->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
