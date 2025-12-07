@extends('layouts.auth')

@section('card-title', 'Bine ai revenit!')
@section('card-subtitle', 'Introdu datele de autentificare pentru a accesa panoul ContaFlux.')

@section('content')
    <form method="POST" action="{{ route('login') }}" class="text-start">
        @csrf
        <div class="form-group my-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email" value="schiau.m.sebastianadrian25@stud.rau.ro" required autofocus>
        </div>
        @error('email')
        <small class="text-danger d-block mb-2">{{ $message }}</small>
        @enderror

        <div class="form-group mb-3">
            <label class="form-label">Parolă</label>
            <input type="password" class="form-control" name="password" value="parola123" required>
        </div>

        <div class="form-check form-switch d-flex align-items-center mb-3">
            <input class="form-check-input" type="checkbox" id="remember" name="remember">
            <label class="form-check-label ms-2" for="remember">Ține-mă minte</label>
        </div>

        <div class="text-center">
            <button type="submit" class="btn bg-gradient-primary w-100 my-4 mb-2">Autentificare</button>
        </div>
        <p class="mt-4 text-sm text-center">
            Nu ai cont?
            <a href="{{ route('register') }}" class="text-primary text-gradient font-weight-bold">Înregistrează-te</a>
        </p>
    </form>
@endsection
