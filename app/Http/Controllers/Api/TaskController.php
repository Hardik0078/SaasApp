<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct(private readonly TaskService $tasks)
    {
    }

    public function index(): JsonResponse
    {
        $this->authorize('viewAny', Task::class);

        return response()->json($this->tasks->paginate());
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', Task::class);

        $task = $this->tasks->create($request->validate([
            'project_id' => ['required', 'exists:projects,id'],
            'assigned_user_id' => ['nullable', 'exists:users,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'priority' => ['required', 'in:low,medium,high,urgent'],
            'due_date' => ['nullable', 'date'],
            'estimated_hours' => ['nullable', 'numeric', 'min:0'],
            'actual_hours' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:todo,in_progress,blocked,done'],
        ]));

        return response()->json($task, 201);
    }

    public function show($tenant,Task $task): JsonResponse
    {
        $this->authorize('view', $task);

        return response()->json($task->load(['project', 'assignedUser', 'comments.user', 'histories.user']));
    }

    public function update(Request $request, $tenant, Task $task): JsonResponse
    {
        $this->authorize('update', $task);

        $task = $this->tasks->update($task, $request->validate([
            'project_id' => ['required', 'exists:projects,id'],
            'assigned_user_id' => ['nullable', 'exists:users,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'priority' => ['required', 'in:low,medium,high,urgent'],
            'due_date' => ['nullable', 'date'],
            'estimated_hours' => ['nullable', 'numeric', 'min:0'],
            'actual_hours' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:todo,in_progress,blocked,done'],
        ]));

        return response()->json($task);
    }
}
