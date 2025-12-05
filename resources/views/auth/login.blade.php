@extends('layouts.auth')

@section('title', 'Autentificare')

@section('content')
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
            @error('email')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Parolă</label>
            <input type="password" class="form-control" name="password" required>
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="remember" id="remember">
            <label class="form-check-label" for="remember">Ține-mă minte</label>
        </div>
        <button type="submit" class="btn btn-primary w-100">Intră în cont</button>
    </form>
    <div class="text-center mt-3">
        <small>Nu ai cont? <a href="{{ route('register') }}">Înregistrează-te</a></small>
    </div>
@endsection
