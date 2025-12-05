<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $companyId = $request->user()->company_id;

        $tasks = Task::with(['company', 'user'])
            ->where('company_id', $companyId)
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->input('status')))
            ->latest()
            ->paginate()
            ->withQueryString();

        if ($request->wantsJson()) {
            return $tasks;
        }

        return view('tasks.index', [
            'tasks' => $tasks,
            'company' => \App\Models\Company::findOrFail($companyId),
            'filters' => $request->only(['status']),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'nullable|integer|exists:users,id',
            'title' => 'required|string',
            'description' => 'nullable|string',
            'status' => 'nullable|in:open,in_progress,done',
            'due_date' => 'nullable|date',
            'priority' => 'nullable|string',
        ]);

        if (! empty($data['user_id']) && ! User::where('company_id', $request->user()->company_id)->whereKey($data['user_id'])->exists()) {
            abort(403, 'Responsabilul trebuie să aparțină companiei tale.');
        }

        $task = Task::create(array_merge($data, [
            'company_id' => $request->user()->company_id,
        ]));

        if ($request->wantsJson()) {
            return response()->json($task->load(['company', 'user']), 201);
        }

        return redirect()->route('tasks.index')->with('status', 'Task-ul a fost creat.');
    }

    public function show(Task $task)
    {
        $this->authorizeCompany($task->company_id);
        return $task->load(['company', 'user']);
    }

    public function update(Request $request, Task $task)
    {
        $this->authorizeCompany($task->company_id);
        $data = $request->validate([
            'title' => 'sometimes|string',
            'description' => 'nullable|string',
            'status' => 'nullable|in:open,in_progress,done',
            'due_date' => 'nullable|date',
            'priority' => 'nullable|string',
        ]);

        $task->update($data);

        if ($request->wantsJson()) {
            return response()->json($task->load(['company', 'user']));
        }

        return redirect()->route('tasks.index')->with('status', 'Task-ul a fost actualizat.');
    }

    public function destroy(Task $task)
    {
        $this->authorizeCompany($task->company_id);
        $task->delete();

        if (request()->wantsJson()) {
            return response()->noContent();
        }

        return redirect()->route('tasks.index')->with('status', 'Task-ul a fost șters.');
    }

    private function authorizeCompany(int $companyId): void
    {
        if ($companyId !== auth()->user()->company_id) {
            abort(403, 'Nu ai acces la acest task.');
        }
    }
}
