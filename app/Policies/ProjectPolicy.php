<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['Super Admin', 'Company Admin', 'Manager', 'Employee']);
    }

    public function view(User $user, Project $project): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        if ($user->tenant_id !== $project->tenant_id) {
            return false;
        }

        if ($user->hasRole('Employee')) {
            return $project->assigned_user_id === $user->id;
        }

        if ($user->hasRole('Manager') || $user->hasRole('Company Admin')) {
            return true;
        }
        return false;

    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['Super Admin', 'Company Admin', 'Manager']);
    }

    public function update(User $user, Project $project): bool
    {
        return ($user->tenant_id === $project->tenant_id && $user->hasAnyRole(['Company Admin', 'Manager']))
            || $user->hasRole('Super Admin');
    }
}
