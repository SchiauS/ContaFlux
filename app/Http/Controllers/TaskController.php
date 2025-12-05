<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::with(['company', 'user'])->latest();

        if ($request->filled('company_id')) {
            $query->where('company_id', $request->integer('company_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $tasks = $query->paginate()->withQueryString();

        if ($request->wantsJson()) {
            return $tasks;
        }

        return view('tasks.index', [
            'tasks' => $tasks,
            'companies' => \App\Models\Company::orderBy('name')->pluck('name', 'id'),
            'filters' => $request->only(['company_id', 'status']),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'company_id' => 'required|integer|exists:companies,id',
            'user_id' => 'nullable|integer|exists:users,id',
            'title' => 'required|string',
            'description' => 'nullable|string',
            'status' => 'nullable|in:open,in_progress,done',
            'due_date' => 'nullable|date',
            'priority' => 'nullable|string',
        ]);

        $task = Task::create($data);

        if ($request->wantsJson()) {
            return response()->json($task->load(['company', 'user']), 201);
        }

        return redirect()->route('tasks.index')->with('status', 'Task-ul a fost creat.');
    }

    public function show(Task $task)
    {
        return $task->load(['company', 'user']);
    }

    public function update(Request $request, Task $task)
    {
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
        $task->delete();

        if (request()->wantsJson()) {
            return response()->noContent();
        }

        return redirect()->route('tasks.index')->with('status', 'Task-ul a fost șters.');
    }
}
