@extends('layouts.app')

@section('title', 'Global View - Companii')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h6 class="mb-0">Companii</h6>
                        <p class="text-sm text-muted mb-0">Vizualizează și gestionează companiile din platformă.</p>
                    </div>
                    <span class="badge bg-gradient-primary">Global</span>
                </div>
                <div class="card-body">
                    <p class="text-sm mb-4">
                        Această pagină este dedicată echipei de administrare pentru o imagine de ansamblu asupra companiilor înscrise.
                        Folosește-o pentru a înțelege rapid starea actuală, licitațiile active și recomandările în curs.
                    </p>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="border rounded-3 p-3 h-100">
                                <h6 class="mb-2"><i class="fa-solid fa-building text-primary me-2"></i>Total companii</h6>
                                <p class="text-sm text-muted mb-0">Panou sumar pentru companiile înregistrate și statusul lor actual.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded-3 p-3 h-100">
                                <h6 class="mb-2"><i class="fa-solid fa-clipboard-check text-primary me-2"></i>Licitații</h6>
                                <p class="text-sm text-muted mb-0">Secțiune dedicată urmăririi licitațiilor lansate și a rezultatelor.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded-3 p-3 h-100">
                                <h6 class="mb-2"><i class="fa-solid fa-handshake text-primary me-2"></i>Recomandări</h6>
                                <p class="text-sm text-muted mb-0">Monitorizează recomandările primite și performanța per partener.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
