<?php

namespace App\Contracts;

use App\Models\Task;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface TaskRepositoryInterface
{
    public function paginateForCurrentTenant(): LengthAwarePaginator;

    public function create(array $attributes): Task;

    public function update(Task $task, array $attributes): Task;
}
