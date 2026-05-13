<?php

namespace App\Services;

use App\Contracts\ProjectRepositoryInterface;
use App\Models\Project;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProjectService
{
    public function __construct(private readonly ProjectRepositoryInterface $projects)
    {
    }

    public function paginate(): LengthAwarePaginator
    {
        return $this->projects->paginateForCurrentTenant();
    }

    public function create(array $attributes): Project
    {
        return $this->projects->create($attributes);
    }

    public function update(Project $project, array $attributes): Project
    {
        return $this->projects->update($project, $attributes);
    }
}
