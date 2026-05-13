<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\View\View;

class SuperAdminController extends Controller
{
    public function index(): View
    {
        return view('central.super-admin-dashboard', [
            'tenants' => Tenant::query()->latest()->paginate(12),
        ]);
    }
}
