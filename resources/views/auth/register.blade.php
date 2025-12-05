@extends('layouts.auth')

@section('card-title', 'Creează-ți contul')
@section('card-subtitle', 'Pornește analiza contabilă asistată de AI în câteva minute.')

@section('content')
    <form method="POST" action="{{ route('register') }}" class="text-start">
        @csrf
        <div class="row">
            <div class="col-12">
                <h6 class="text-uppercase text-body text-xs font-weight-bolder">Date personale</h6>
            </div>
            <div class="col-12">
                <div class="input-group input-group-outline my-3">
                    <label class="form-label">Nume complet</label>
                    <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                </div>
                @error('name')
                <small class="text-danger d-block mb-2">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-12">
                <div class="input-group input-group-outline mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                </div>
                @error('email')
                <small class="text-danger d-block mb-2">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-6">
                <div class="input-group input-group-outline mb-3">
                    <label class="form-label">Parolă</label>
                    <input type="password" class="form-control" name="password" required>
                </div>
                @error('password')
                <small class="text-danger d-block mb-2">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-6">
                <div class="input-group input-group-outline mb-3">
                    <label class="form-label">Confirmare parolă</label>
                    <input type="password" class="form-control" name="password_confirmation" required>
                </div>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-12">
                <h6 class="text-uppercase text-body text-xs font-weight-bolder">Setări companie</h6>
            </div>
            <div class="col-12">
                <div class="input-group input-group-outline my-3">
                    <label class="form-label">Nume companie</label>
                    <input type="text" class="form-control" name="company_name" value="{{ old('company_name') }}" required>
                </div>
                @error('company_name')
                <small class="text-danger d-block mb-2">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-4">
                <div class="input-group input-group-outline mb-3">
                    <label class="form-label">Monedă</label>
                    <input type="text" class="form-control" name="currency" maxlength="3" value="{{ old('currency', 'RON') }}">
                </div>
            </div>
            <div class="col-md-4">
                <div class="input-group input-group-outline mb-3">
                    <label class="form-label">Fus orar</label>
                    <input type="text" class="form-control" name="timezone" value="{{ old('timezone', 'Europe/Bucharest') }}">
                </div>
            </div>
            <div class="col-md-4">
                <div class="input-group input-group-outline mb-3">
                    <label class="form-label">Program lucru</label>
                    <input type="text" class="form-control" name="working_hours" placeholder="09:00-18:00" value="{{ old('working_hours') }}">
                </div>
            </div>
            <div class="col-12">
                <div class="input-group input-group-outline mb-3">
                    <label class="form-label">Roluri / poziții</label>
                    <input type="text" class="form-control" name="positions" placeholder="Contabil, Manager, Operator" value="{{ old('positions') }}">
                </div>
            </div>
        </div>

        <div class="text-center">
            <button type="submit" class="btn bg-gradient-primary w-100 my-4 mb-2">Creează cont și companie</button>
        </div>
        <p class="mt-4 text-sm text-center">
            Ai deja cont?
            <a href="{{ route('login') }}" class="text-primary text-gradient font-weight-bold">Autentifică-te</a>
        </p>
    </form>
@endsection
