<?php

use App\Models\Tenant;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('tenant.{tenantId}', function ($user, string $tenantId) {
    return $user->tenant_id === $tenantId || $user->hasRole('Super Admin');
});
