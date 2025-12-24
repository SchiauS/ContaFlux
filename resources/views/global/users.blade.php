@extends('layouts.app')

@section('title', 'Global View - Utilizatori')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h6 class="mb-0">Utilizatori</h6>
                        <p class="text-sm text-muted mb-0">Administrare centralizată pentru utilizatorii tuturor companiilor.</p>
                    </div>
                    <span class="badge bg-gradient-primary">Global</span>
                </div>
                <div class="card-body">
                    <p class="text-sm mb-4">
                        Vizualizează rapid conturile active, rolurile și nivelurile de acces. Secțiunea este gândită pentru a oferi
                        echipei CARZEN control deplin asupra utilizatorilor din platformă.
                    </p>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="border rounded-3 p-3 h-100">
                                <h6 class="mb-2"><i class="fa-solid fa-users text-primary me-2"></i>Activitate</h6>
                                <p class="text-sm text-muted mb-0">Indicatori privind activitatea recentă și autentificările.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded-3 p-3 h-100">
                                <h6 class="mb-2"><i class="fa-solid fa-shield-halved text-primary me-2"></i>Roluri și permisiuni</h6>
                                <p class="text-sm text-muted mb-0">Înțelege rapid cine are drepturi de administrare și cine are acces limitat.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded-3 p-3 h-100">
                                <h6 class="mb-2"><i class="fa-solid fa-envelope-circle-check text-primary me-2"></i>Validări</h6>
                                <p class="text-sm text-muted mb-0">Statusul verificărilor de email și onboarding pentru conturi noi.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
