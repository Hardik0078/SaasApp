<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImpersonateTenantAsSuperAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // Impersonate only if not logged in and X-Super-Admin-Token header is present
        if (!Auth::check() && $request->hasHeader('X-Super-Admin-Token')) {
            $token = $request->header('X-Super-Admin-Token');
            if ($token === config('app.super_admin_token')) {
                $user = \App\Models\User::role('Super Admin')->first();
                if ($user) {
                    Auth::login($user);
                }
            }
        }
        return $next($request);
    }
}
