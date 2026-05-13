<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Services\ProjectService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function __construct(private readonly ProjectService $projects)
    {
    }

    public function index(): JsonResponse
    {
        $this->authorize('viewAny', Project::class);

        return response()->json($this->projects->paginate());
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', Project::class);

        $project = $this->projects->create($request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
            'budget' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:draft,active,on_hold,completed,cancelled'],
            'owner_id' => ['nullable', 'exists:users,id'],
        ]));

        return response()->json($project, 201);
    }

    public function show($tenant,Project $project): JsonResponse
    {
        $this->authorize('view', $project);

        return response()->json($project->load(['owner', 'tasks.assignedUser']));
    }

    public function update(Request $request, $tenant, Project $project): JsonResponse
    {
        $this->authorize('update', $project);

        $project = $this->projects->update($project, $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
            'budget' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:draft,active,on_hold,completed,cancelled'],
            'owner_id' => ['nullable', 'exists:users,id'],
        ]));

        return response()->json($project);
    }
}
