<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['Super Admin', 'Company Admin', 'Manager', 'Employee']);
    }

    public function view(User $user, Task $task): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        if ($user->tenant_id !== $task->tenant_id) {
            return false;
        }

        if ($user->hasRole('Employee')) {
            return $task->assigned_user_id === $user->id;
        }

        if ($user->hasRole('Manager') || $user->hasRole('Company Admin')) {
            return true;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['Super Admin', 'Company Admin', 'Manager','Employee']);
    }

    public function update(User $user, Task $task): bool
    {
        return ($user->tenant_id === $task->tenant_id && $user->hasAnyRole(['Company Admin', 'Manager','Employee']))
            || $task->assigned_user_id === $user->id
            || $user->hasRole('Super Admin');
    }
}
