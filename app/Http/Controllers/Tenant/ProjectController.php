<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use App\Services\ProjectService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function __construct(private readonly ProjectService $projects)
    {
    }

    public function index(): View
    {
        $this->authorize('viewAny', Project::class);

        return view('tenant.projects.index', [
            'projects' => $this->projects->paginate(),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Project::class);

        return view('tenant.projects.create', [
            'users' => User::query()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Project::class);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'budget' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:draft,active,on_hold,completed,cancelled'],
            'owner_id' => ['nullable', 'exists:users,id'],
        ]);

        $this->projects->create($data);

        return back()->with('status', 'Project created.');
    }

    public function show($tenant,Project $project): View
    {
        $this->authorize('view', $project);

        return view('tenant.projects.show', [
            'project' => $project->load(['owner', 'tasks.assignedUser', 'tasks.comments.user']),
        ]);
    }

    public function edit($tenant,Project $project): View
    {
        $this->authorize('update', $project);

        return view('tenant.projects.edit', [
            'project' => $project,
            'users' => User::query()->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request,$tenant, Project $project): RedirectResponse
    {
        $this->authorize('update', $project);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'budget' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:draft,active,on_hold,completed,cancelled'],
            'owner_id' => ['nullable', 'exists:users,id'],
        ]);

        $this->projects->update($project, $data);

        return redirect()->route('tenant.projects.show', ['tenant' => tenant(), 'project' => $project])
            ->with('status', 'Project updated.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $this->authorize('update', $project);
        $project->delete();

        return redirect()->route('tenant.projects.index', ['tenant' => tenant()])
            ->with('status', 'Project archived.');
    }
}
