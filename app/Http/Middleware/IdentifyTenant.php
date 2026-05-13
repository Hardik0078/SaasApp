<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Spatie\Permission\PermissionRegistrar;
use Symfony\Component\HttpFoundation\Response;

class IdentifyTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        $tenant = $request->route('tenant');

        if (is_string($tenant)) {
            $tenant = Tenant::query()->where('slug', $tenant)->first();
        }

        if (! $tenant instanceof Tenant) {
            $slug = $request->header('X-Tenant');

            if ($slug) {
                $tenant = Tenant::query()->where('slug', $slug)->first();
            }
        }

        abort_unless($tenant instanceof Tenant, 404, 'Tenant could not be resolved.');

        tenancy()->initialize($tenant);
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return $next($request);
    }
}
