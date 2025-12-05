@extends('layouts.app')

@section('title', 'Task-uri')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                    <div>
                        <h5 class="mb-0">Task-uri</h5>
                        <span class="text-sm text-muted">Companie: {{ $company->name }}</span>
                    </div>
                    <button class="btn btn-primary btn-sm mt-3 mt-md-0" type="button" data-bs-toggle="collapse" data-bs-target="#createTaskForm">
                        <i class="fa-solid fa-plus me-1"></i> Adaugă task
                    </button>
                </div>
                <div id="createTaskForm" class="collapse border-bottom">
                    <form class="p-4" method="POST" action="{{ route('tasks.store') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label text-sm">Titlu</label>
                                <input type="text" class="form-control" name="title" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-sm">Responsabil (ID user)</label>
                                <input type="number" class="form-control" name="user_id">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-sm">Status</label>
                                <select name="status" class="form-select">
                                    <option value="open">Open</option>
                                    <option value="in_progress">In progress</option>
                                    <option value="done">Done</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-sm">Prioritate</label>
                                <input type="text" class="form-control" name="priority">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-sm">Termen</label>
                                <input type="date" class="form-control" name="due_date">
                            </div>
                            <div class="col-12">
                                <label class="form-label text-sm">Descriere</label>
                                <textarea class="form-control" name="description" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="mt-3 d-flex justify-content-end">
                            <button class="btn btn-success" type="submit">
                                <i class="fa-solid fa-save me-1"></i> Salvează
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-body border-bottom">
                    <form class="row g-3" method="GET">
                        <div class="col-md-6">
                            <label class="form-label text-sm">Status</label>
                            <select name="status" class="form-select">
                                <option value="">Toate</option>
                                <option value="open" @selected(($filters['status'] ?? '') === 'open')>În lucru</option>
                                <option value="in_progress" @selected(($filters['status'] ?? '') === 'in_progress')>În derulare</option>
                                <option value="done" @selected(($filters['status'] ?? '') === 'done')>Finalizat</option>
                            </select>
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <button class="btn btn-primary w-100" type="submit">
                                <i class="fa-solid fa-filter"></i> Filtrează
                            </button>
                        </div>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center mb-0">
                        <thead>
                        <tr>
                            <th>Task</th>
                            <th>Responsabil</th>
                            <th>Prioritate</th>
                            <th>Status</th>
                            <th>Termen</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($tasks as $task)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $task->title }}</div>
                                    <div class="text-muted text-sm">{{ $task->description ?? '—' }}</div>
                                </td>
                                <td class="text-sm">{{ optional($task->user)->name ?? 'Nespecificat' }}</td>
                                <td>
                                    <span class="badge bg-gradient-info text-uppercase">{{ $task->priority ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    <span class="badge
                                        @class([
                                            'bg-gradient-success' => $task->status === 'done',
                                            'bg-gradient-warning' => $task->status === 'in_progress',
                                            'bg-gradient-secondary' => $task->status === 'open',
                                            'bg-gradient-secondary' => empty($task->status)
                                    ])">
                                        {{ ucfirst($task->status ?? 'open') }} {{$task->status }}
                                    </span>
                                </td>
                                <td class="text-sm">{{ optional($task->due_date)->format('d M Y') ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    Nu există task-uri pentru filtrele selectate.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                @if($tasks->hasPages())
                    <div class="card-footer">
                        {{ $tasks->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
