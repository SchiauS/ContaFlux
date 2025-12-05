@extends('layouts.app')

@section('title', 'Setări companie')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-gradient-primary text-white">
                <h5 class="mb-0">Profil companie</h5>
                <small>Actualizează datele și preferințele organizației tale.</small>
            </div>
            <form method="POST" action="{{ route('companies.update', $company) }}">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nume</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $company->name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Cod fiscal</label>
                            <input type="text" name="fiscal_code" class="form-control" value="{{ old('fiscal_code', $company->fiscal_code) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Monedă</label>
                            <input type="text" name="currency" class="form-control" value="{{ old('currency', $company->currency ?? 'RON') }}" maxlength="3">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Fus orar</label>
                            <input type="text" name="timezone" class="form-control" value="{{ old('timezone', $company->timezone ?? 'Europe/Bucharest') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Început exercițiu</label>
                            <input type="date" name="fiscal_year_start" class="form-control" value="{{ old('fiscal_year_start', optional($company->fiscal_year_start)->toDateString()) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Program de lucru</label>
                            <input type="text" name="settings[working_hours]" class="form-control" value="{{ old('settings.working_hours', data_get($company->settings, 'working_hours')) }}" placeholder="09:00-18:00">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Roluri/poziții</label>
                            <input type="text" name="settings[positions]" class="form-control" value="{{ old('settings.positions', collect(data_get($company->settings, 'positions', []))->join(', ')) }}" placeholder="Contabil, Manager, Operator">
                            <small class="text-muted">Separă pozițiile cu virgulă.</small>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-between align-items-center">
                    <div class="text-muted text-sm">Conturi: {{ $company->accounts_count }} · Task-uri: {{ $company->tasks_count }}</div>
                    <button type="submit" class="btn btn-success">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Salvează setările
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card h-100 shadow-sm">
            <div class="card-body">
                <h6 class="mb-3">Angajați și roluri</h6>
                <p class="text-sm text-muted">Această secțiune va afișa în viitor angajații companiei și rolurile lor. Momentan accesul este limitat la administratorul care a creat organizația.</p>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Administrator curent
                        <span class="badge bg-gradient-primary">{{ auth()->user()->name }}</span>
                    </li>
                    <li class="list-group-item">
                        Program: {{ data_get($company->settings, 'working_hours', 'Nesetat') }}
                    </li>
                    <li class="list-group-item">
                        Poziții: {{ collect(data_get($company->settings, 'positions', []))->filter()->join(', ') ?: 'Nespecificate' }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
