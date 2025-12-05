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
                                        <form class="d-flex align-items-center gap-2" method="POST" action="{{ route('employees.time-entries.store', $employee) }}">
                                            @csrf
                                            <input type="date" name="worked_on" class="form-control form-control-sm" value="{{ now()->toDateString() }}" required>
                                            <input type="number" name="hours" class="form-control form-control-sm" step="0.25" min="0" max="24" placeholder="Ore" required>
                                            <button class="btn btn-sm btn-outline-primary" type="submit">Pontaj</button>
                                        </form>
                                        <form class="d-flex align-items-center gap-2" method="POST" action="{{ route('employees.leaves.store', $employee) }}">
                                            @csrf
                                            <input type="text" name="type" class="form-control form-control-sm" placeholder="Tip concediu" required>
                                            <input type="date" name="start_date" class="form-control form-control-sm" required>
                                            <input type="date" name="end_date" class="form-control form-control-sm" required>
                                            <button class="btn btn-sm btn-outline-warning text-dark" type="submit">Concediu</button>
                                        </form>
                                        <form class="d-flex align-items-center gap-2" method="POST" action="{{ route('employees.payroll', $employee) }}">
                                            @csrf
                                            <input type="number" step="0.01" min="0" name="amount" class="form-control form-control-sm"
                                                   value="{{ $employee->salary }}" required>
                                            <input type="date" name="paid_at" class="form-control form-control-sm" value="{{ now()->toDateString() }}" required>
                                            <select name="financial_account_id" class="form-select form-select-sm" required>
                                                <option value="">Cont</option>
                                                @foreach($accounts as $account)
                                                    <option value="{{ $account->id }}">{{ $account->name }}</option>
                                                @endforeach
                                            </select>
                                            <button class="btn btn-sm btn-outline-success" type="submit">Salariu</button>
                                        </form>
                                        <div class="d-flex gap-2">
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
