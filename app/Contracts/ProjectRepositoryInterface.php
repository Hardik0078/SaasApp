<?php

namespace App\Contracts;

use App\Models\Project;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ProjectRepositoryInterface
{
    public function paginateForCurrentTenant(): LengthAwarePaginator;

    public function create(array $attributes): Project;

    public function update(Project $project, array $attributes): Project;
}
