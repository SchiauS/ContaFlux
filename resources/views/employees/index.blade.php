@extends('layouts.app')

@section('title', 'Angajați')

@section('content')
    <div class="row">
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6 class="mb-0">Adaugă angajat</h6>
                    <p class="text-sm text-muted mb-0">Completează datele de bază și salariul curent.</p>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('employees.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Nume</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="optional">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Rol / Departament</label>
                            <input type="text" name="role" class="form-control" placeholder="Ex: Contabil">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Salariu lunar (RON)</label>
                            <input type="number" step="0.01" min="0" name="salary" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-user-plus me-1"></i> Adaugă</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h6 class="mb-0">Echipa curentă</h6>
                        <p class="text-sm text-muted mb-0">Pontaj, concedii, plăți salariale și stare contract.</p>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center mb-0">
                        <thead>
                        <tr>
                            <th>Nume</th>
                            <th>Rol</th>
                            <th>Salariu</th>
                            <th>Status</th>
                            <th>Acțiuni</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($employees as $employee)
                            <tr>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="mb-0 text-sm fw-bold">{{ $employee->name }}</span>
                                        <small class="text-muted">{{ $employee->email ?? 'Email necompletat' }}</small>
                                    </div>
                                </td>
                                <td>{{ $employee->role ?? 'Nespecificat' }}</td>
                                <td>{{ number_format($employee->salary, 2, '.', ' ') }} {{ $employee->currency }}</td>
                                <td>
                                    <span class="badge {{ $employee->status === 'active' ? 'bg-gradient-success' : 'bg-gradient-secondary' }}">
                                        {{ $employee->status === 'active' ? 'Activ' : 'Concediat' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-2">
                                        <div class="d-flex flex-wrap gap-2">
                                            <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#timeEntryModal-{{ $employee->id }}">
                                                Pontaj
                                            </button>
                                            <button class="btn btn-sm btn-outline-warning text-dark" type="button" data-bs-toggle="modal" data-bs-target="#leaveModal-{{ $employee->id }}">
                                                Concediu
                                            </button>
                                            <button class="btn btn-sm btn-outline-success" type="button" data-bs-toggle="modal" data-bs-target="#payrollModal-{{ $employee->id }}">
                                                Salariu
                                            </button>
                                        </div>
                                        <div class="d-flex flex-wrap gap-2">
                                            @if($employee->status === 'active')
                                                <form method="POST" action="{{ route('employees.terminate', $employee) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button class="btn btn-sm btn-outline-danger" type="submit">Concediază</button>
                                                </form>
                                            @else
                                                <form method="POST" action="{{ route('employees.reinstate', $employee) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button class="btn btn-sm btn-outline-secondary" type="submit">Reactivează</button>
                                                </form>
                                            @endif
                                            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="collapse" data-bs-target="#details-{{ $employee->id }}">
                                                Istoric
                                            </button>
                                        </div>
                                        <button class="btn btn-sm btn-outline-danger align-self-start js-delete-trigger" type="button"
                                                data-delete-url="{{ route('employees.destroy', $employee) }}"
                                                data-item-name="angajatul {{ $employee->name }}">
                                            <i class="fa-solid fa-trash"></i> Șterge
                                        </button>
                                    </div>
                                    <div class="modal fade" id="timeEntryModal-{{ $employee->id }}" tabindex="-1" aria-labelledby="timeEntryLabel-{{ $employee->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="timeEntryLabel-{{ $employee->id }}">Pontaj pentru {{ $employee->name }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form method="POST" action="{{ route('employees.time-entries.store', $employee) }}">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Data</label>
                                                            <input type="date" name="worked_on" class="form-control" value="{{ now()->toDateString() }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Ore lucrate</label>
                                                            <input type="number" name="hours" class="form-control" step="0.25" min="0" max="24" placeholder="Ore" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Notițe (opțional)</label>
                                                            <input type="text" name="note" class="form-control" placeholder="Ex: proiect X">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Anulează</button>
                                                        <button class="btn btn-primary" type="submit">Salvează pontaj</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="leaveModal-{{ $employee->id }}" tabindex="-1" aria-labelledby="leaveLabel-{{ $employee->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="leaveLabel-{{ $employee->id }}">Concediu pentru {{ $employee->name }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form method="POST" action="{{ route('employees.leaves.store', $employee) }}">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Tip concediu</label>
                                                            <input type="text" name="type" class="form-control" placeholder="Tip concediu" required>
                                                        </div>
                                                        <div class="row g-3">
                                                            <div class="col-sm-6">
                                                                <label class="form-label">Începe</label>
                                                                <input type="date" name="start_date" class="form-control" required>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <label class="form-label">Se termină</label>
                                                                <input type="date" name="end_date" class="form-control" required>
                                                            </div>
                                                        </div>
                                                        <div class="mt-3">
                                                            <label class="form-label">Comentarii (opțional)</label>
                                                            <textarea name="comment" class="form-control" rows="2" placeholder="Motiv, detalii"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Anulează</button>
                                                        <button class="btn btn-warning text-dark" type="submit">Trimite solicitare</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="payrollModal-{{ $employee->id }}" tabindex="-1" aria-labelledby="payrollLabel-{{ $employee->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="payrollLabel-{{ $employee->id }}">Plată salariu către {{ $employee->name }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form method="POST" action="{{ route('employees.payroll', $employee) }}">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Suma</label>
                                                            <input type="number" step="0.01" min="0" name="amount" class="form-control" value="{{ $employee->salary }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Data plății</label>
                                                            <input type="date" name="paid_at" class="form-control" value="{{ now()->toDateString() }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Cont financiar</label>
                                                            <select name="financial_account_id" class="form-select" required>
                                                                <option value="">Selectează cont</option>
                                                                @foreach($accounts as $account)
                                                                    <option value="{{ $account->id }}">{{ $account->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Detalii (opțional)</label>
                                                            <input type="text" name="note" class="form-control" placeholder="Ex: bonus, perioadă, proiect">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Anulează</button>
                                                        <button class="btn btn-success" type="submit">Procesează plata</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr class="collapse" id="details-{{ $employee->id }}">
                                <td colspan="5">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="text-sm">Pontaje recente</h6>
                                            <ul class="list-group list-group-flush">
                                                @forelse($employee->timeEntries as $entry)
                                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                                        <span>{{ $entry->worked_on->format('d M') }} - {{ $entry->note ?? 'Pontaj' }}</span>
                                                        <span class="fw-bold">{{ $entry->hours }} h</span>
                                                    </li>
                                                @empty
                                                    <li class="list-group-item px-0">Nu există pontaje.</li>
                                                @endforelse
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="text-sm">Concedii</h6>
                                            <ul class="list-group list-group-flush">
                                                @forelse($employee->leaves as $leave)
                                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                                        <div>
                                                            <div class="fw-bold">{{ $leave->type }}</div>
                                                            <small class="text-muted">{{ $leave->start_date->format('d M') }} - {{ $leave->end_date->format('d M') }}</small>
                                                        </div>
                                                        <span class="badge bg-gradient-info">{{ ucfirst($leave->status) }}</span>
                                                    </li>
                                                @empty
                                                    <li class="list-group-item px-0">Nu există concedii.</li>
                                                @endforelse
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Nu există angajați înregistrați.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
