<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CompanySubscription;
use Illuminate\Http\JsonResponse;

class SubscriptionController extends Controller
{
    public function show(): JsonResponse
    {
        abort_unless(request()->user()?->hasAnyRole(['Company Admin', 'Super Admin']), 403);

        return response()->json(
            CompanySubscription::query()->with(['plan', 'tenant'])->latest()->first()
        );
    }
}
