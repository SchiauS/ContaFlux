@extends('layouts.app')

@section('title', 'Tranzacții')

@section('content')
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                    <div>
                        <h5 class="mb-0">Tranzacții financiare</h5>
                        <span class="text-sm text-muted d-inline-block">Companie: {{ $company->name }}</span>
                    </div>
                    <button class="btn btn-primary btn-sm mt-3 mt-md-0" type="button" data-bs-toggle="collapse" data-bs-target="#createTransactionForm">
                        <i class="fa-solid fa-plus me-1"></i> Adaugă
                    </button>
                </div>
                <div id="createTransactionForm" class="collapse border-bottom">
                    <form class="p-4" method="POST" action="{{ route('transactions.store') }}">
                        @csrf
                        <div class="row g-3">
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
                <div class="table-responsive">
                    <table class="table align-items-center mb-0">
                        <thead>
                        <tr>
                            <th>Descriere</th>
                            <th>Cont</th>
                            <th>Direcție</th>
                            <th>Suma</th>
                            <th>Data</th>
                            <th class="text-end">Acțiuni</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($transactions as $transaction)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $transaction->description ?? '—' }}</div>
                                    <div class="text-muted text-sm">{{ $transaction->counterparty ?? 'N/A' }}</div>
                                </td>
                                <td class="text-sm">{{ optional($transaction->account)->code ?? '—' }}</td>
                                <td>
                                    <span class="badge {{ $transaction->direction === 'credit' ? 'bg-gradient-success' : 'bg-gradient-danger' }}">
                                        {{ ucfirst($transaction->direction) }}
                                    </span>
                                </td>
                                <td class="text-sm fw-bold">{{ number_format($transaction->amount, 2, '.', ' ') }} {{ $transaction->currency }}</td>
                                <td class="text-sm text-muted">{{ optional($transaction->occurred_at)->format('d M Y') }}</td>
                                <td class="text-end">
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-sm btn-outline-primary" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#editTransaction-{{ $transaction->id }}"
                                                data-edit-transaction
                                                data-target="#editTransaction-{{ $transaction->id }}"
                                                data-current-account="{{ $transaction->financial_account_id }}">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger js-delete-trigger" type="button"
                                                data-delete-url="{{ route('transactions.destroy', $transaction) }}"
                                                data-item-name="această tranzacție">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr id="editTransaction-{{ $transaction->id }}" class="collapse bg-light">
                                <td colspan="6" class="p-4">
                                    <form method="POST" action="{{ route('transactions.update', $transaction) }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label text-sm">Cont</label>
                                                <select name="financial_account_id" class="form-select" required>
                                                    @foreach($accounts as $id => $code)
                                                        <option value="{{ $id }}" @selected($transaction->financial_account_id == $id)>
                                                            {{ $code }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <input type="hidden" name="current_financial_account_id" value="{{ $transaction->financial_account_id }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label text-sm">Direcție</label>
                                                <select name="direction" class="form-select">
                                                    <option value="credit" @selected($transaction->direction === 'credit')>Credit</option>
                                                    <option value="debit" @selected($transaction->direction === 'debit')>Debit</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label text-sm">Sumă</label>
                                                <input type="number" step="0.01" class="form-control" name="amount" value="{{ $transaction->amount }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label text-sm">Monedă</label>
                                                <input type="text" class="form-control" name="currency" value="{{ $transaction->currency }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label text-sm">Data</label>
                                                <input type="date" class="form-control" name="occurred_at" value="{{ optional($transaction->occurred_at)->format('Y-m-d') }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label text-sm">Partener</label>
                                                <input type="text" class="form-control" name="counterparty" value="{{ $transaction->counterparty }}">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label text-sm">Descriere</label>
                                                <textarea class="form-control" name="description" rows="2">{{ $transaction->description }}</textarea>
                                            </div>
                                        </div>
                                        <div class="mt-3 d-flex justify-content-end">
                                            <button class="btn btn-success" type="submit">
                                                <i class="fa-solid fa-floppy-disk me-1"></i> Actualizează
                                            </button>
                                        </div>
                                    </form>
                                </td>
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
