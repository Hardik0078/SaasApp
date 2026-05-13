<?php

namespace App\Providers;

use App\Contracts\ProjectRepositoryInterface;
use App\Contracts\TaskRepositoryInterface;
use App\Models\Project;
use App\Models\Task;
use App\Policies\ProjectPolicy;
use App\Policies\TaskPolicy;
use App\Repositories\EloquentProjectRepository;
use App\Repositories\EloquentTaskRepository;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ProjectRepositoryInterface::class, EloquentProjectRepository::class);
        $this->app->bind(TaskRepositoryInterface::class, EloquentTaskRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Project::class, ProjectPolicy::class);
        Gate::policy(Task::class, TaskPolicy::class);

        Gate::define('access-super-admin', fn ($user) => $user->hasRole('Super Admin'));

        RateLimiter::for('api', function (Request $request) {
            $tenantKey = optional(tenant())->getTenantKey() ?? 'central';

            return [
                Limit::perMinute(120)->by($tenantKey.'|'.$request->ip()),
                Limit::perMinute(30)->by(optional($request->user())->id ?: $request->ip()),
            ];
        });

        RateLimiter::for('login', function (Request $request) {
            $tenantKey = optional(tenant())->getTenantKey() ?? 'central';

            return Limit::perMinute(5)->by($tenantKey.'|'.$request->input('email').'|'.$request->ip());
        });
    }
}
