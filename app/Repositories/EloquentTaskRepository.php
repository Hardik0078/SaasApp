<?php

namespace App\Repositories;

use App\Contracts\TaskRepositoryInterface;
use App\Models\Task;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentTaskRepository implements TaskRepositoryInterface
{
    public function paginateForCurrentTenant(): LengthAwarePaginator
    {
        $query = Task::query()
            ->with(['project', 'assignedUser', 'comments.user', 'media'])
            ->latest();

        $user = auth()->user();

        if ($user->hasRole('Employee')) {
            $query->where('assigned_user_id', $user->id);
        } elseif ($user->hasRole('Manager')) {
            $managerAndEmployeeIds = User::whereHas('roles', function ($q) {
                $q->whereIn('name', ['Manager', 'Employee']);
            })
            ->where('tenant_id', $user->tenant_id)
            ->pluck('id');

            $query->whereIn('assigned_user_id', $managerAndEmployeeIds);
        }

        return $query->paginate(15);
    }

    public function create(array $attributes): Task
    {
        return Task::query()->create($attributes);
    }

    public function update(Task $task, array $attributes): Task
    {
        $task->update($attributes);

        return $task->refresh();
    }
}
