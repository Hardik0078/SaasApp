<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Services\TaskService;
use Illuminate\Support\Arr;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function __construct(private readonly TaskService $tasks)
    {
    }

    public function index(): View
    {
        $this->authorize('viewAny', Task::class);
        return view('tenant.tasks.index', [
            'tasks' => $this->tasks->paginate(),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Task::class);

        return view('tenant.tasks.create', [
            'projects' => Project::query()->orderBy('name')->get(),
            'users' => User::query()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Task::class);

        $data = $request->validate([
            'project_id' => ['required', 'exists:projects,id'],
            'assigned_user_id' => ['nullable', 'exists:users,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'priority' => ['required', 'in:low,medium,high,urgent'],
            'due_date' => ['nullable', 'date'],
            'estimated_hours' => ['nullable', 'numeric', 'min:0'],
            'actual_hours' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:todo,in_progress,blocked,done'],
        ]);

        $createdTask = $this->tasks->create($data);

         return redirect()->route('tenant.tasks.show', ['tenant' => tenant(), 'task' => $createdTask])
            ->with('status', 'Task Created.');

    }

    public function show($tenant,Task $task): View
    {
        $this->authorize('view', $task);

        return view('tenant.tasks.show', [
            'task' => $task->load(['project', 'assignedUser', 'comments.user', 'histories.user', 'media']),
        ]);
    }

    public function edit($tenant,Task $task): View
    {
        $this->authorize('update', $task);

        return view('tenant.tasks.edit', [
            'task' => $task,
            'projects' => Project::query()->orderBy('name')->get(),
            'users' => User::query()->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, $tenant, Task $task): RedirectResponse
    {
        $this->authorize('update', $task);

        $data = $request->validate([
            'project_id' => ['required', 'exists:projects,id'],
            'assigned_user_id' => ['nullable', 'exists:users,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'priority' => ['required', 'in:low,medium,high,urgent'],
            'due_date' => ['nullable', 'date'],
            'estimated_hours' => ['nullable', 'numeric', 'min:0'],
            'actual_hours' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:todo,in_progress,blocked,done'],
            'comment' => ['nullable', 'string'],
            'attachments.*' => ['nullable', 'file', 'mimes:pdf,png,jpg,jpeg,doc,docx,xlsx,csv,txt', 'max:4096'],
        ]);

        $updatedTask = $this->tasks->update($task, Arr::except($data, ['comment']));

        if (! empty($data['comment'])) {
            $this->tasks->addComment($updatedTask, (int) auth()->id(), $data['comment'], $request->file('attachments', []));
        }

        return redirect()->route('tenant.tasks.show', ['tenant' => tenant(), 'task' => $updatedTask])
            ->with('status', 'Task updated.');
    }
}
