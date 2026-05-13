<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\CompanySubscription;
use App\Models\Project;
use App\Models\Task;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('tenant.dashboard', [
            'projectCount' => Project::query()->count(),
            'openTaskCount' => Task::query()->whereIn('status', ['todo', 'in_progress'])->count(),
            'completedTaskCount' => Task::query()->where('status', 'done')->count(),
            'subscription' => CompanySubscription::query()->with('plan')->latest()->first(),
            'recentTasks' => Task::query()->with(['project', 'assignedUser'])->latest()->take(8)->get(),
        ]);
    }
}
