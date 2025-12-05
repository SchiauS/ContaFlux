@extends('layouts.auth')

@section('title', 'Înregistrare')

@section('content')
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Nume complet</label>
            <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
            @error('name')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
            @error('email')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Parolă</label>
            <input type="password" class="form-control" name="password" required>
            @error('password')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Confirmare parolă</label>
            <input type="password" class="form-control" name="password_confirmation" required>
        </div>
        <hr>
        <div class="mb-3">
            <label class="form-label">Nume companie</label>
            <input type="text" class="form-control" name="company_name" value="{{ old('company_name') }}" required>
            @error('company_name')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Monedă principală</label>
            <input type="text" class="form-control" name="currency" value="{{ old('currency', 'RON') }}" maxlength="3">
        </div>
        <div class="mb-3">
            <label class="form-label">Fus orar</label>
            <input type="text" class="form-control" name="timezone" value="{{ old('timezone', 'Europe/Bucharest') }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Program de lucru (ex: 09:00-18:00)</label>
            <input type="text" class="form-control" name="working_hours" value="{{ old('working_hours') }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Roluri/poziții (separate prin virgulă)</label>
            <input type="text" class="form-control" name="positions" placeholder="Contabil, Manager, Operator" value="{{ old('positions') }}">
        </div>
        <button type="submit" class="btn btn-primary w-100">Creează cont și companie</button>
    </form>
    <div class="text-center mt-3">
        <small>Ai deja cont? <a href="{{ route('login') }}">Autentifică-te</a></small>
    </div>
@endsection
