@extends('layouts.app')

@section('title', 'Task-uri')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                    <div>
                        <h5 class="mb-0">Task-uri</h5>
                        <span class="text-sm text-muted">Companie: {{ optional($company)->name ?? 'Toate' }}</span>
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
                <div class="card-body border-bottom pt-0">
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
                            <button class="btn btn-primary w-100 m-0" type="submit">
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
                            <th class="text-end">Acțiuni</th>
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
                                    <span
                                        @class([
                                            'badge bg-gradient-success' => $task->status === 'done',
                                            'badge bg-gradient-warning' => $task->status === 'in_progress',
                                            'badge bg-gradient-secondary' => $task->status === 'open',
                                            'badge bg-gradient-secondary' => empty($task->status) ])>
                                        {{ ucfirst($task->status ?? 'open') }}
                                    </span>
                                </td>
                                <td class="text-sm">{{ optional($task->due_date)->format('d M Y') ?? '—' }}</td>
                                <td class="text-end">
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#editTask-{{ $task->id }}">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger js-delete-trigger" type="button"
                                                data-delete-url="{{ route('tasks.destroy', $task) }}"
                                                data-item-name="acest task">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr id="editTask-{{ $task->id }}" class="collapse bg-light">
                                <td colspan="6" class="p-4">
                                    <form method="POST" action="{{ route('tasks.update', $task) }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label text-sm">Titlu</label>
                                                <input type="text" class="form-control" name="title" value="{{ $task->title }}" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label text-sm">Responsabil (ID user)</label>
                                                <input type="number" class="form-control" name="user_id" value="{{ $task->user_id }}" disabled>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label text-sm">Status</label>
                                                <select name="status" class="form-select">
                                                    <option value="open" @selected($task->status === 'open')>Open</option>
                                                    <option value="in_progress" @selected($task->status === 'in_progress')>In progress</option>
                                                    <option value="done" @selected($task->status === 'done')>Done</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label text-sm">Prioritate</label>
                                                <input type="text" class="form-control" name="priority" value="{{ $task->priority }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label text-sm">Termen</label>
                                                <input type="date" class="form-control" name="due_date" value="{{ optional($task->due_date)->format('Y-m-d') }}">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label text-sm">Descriere</label>
                                                <textarea class="form-control" name="description" rows="2">{{ $task->description }}</textarea>
                                            </div>
                                        </div>
                                        <div class="mt-3 d-flex justify-content-end">
                                            <button class="btn btn-success" type="submit">
                                                <i class="fa-solid fa-floppy-disk me-1"></i> Actualizează
                                            </button>
                                        </div>
                                    </form>
                                </td>
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
