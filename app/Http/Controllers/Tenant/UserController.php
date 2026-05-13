<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\AuthorizationDefaults;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        abort_unless(auth()->user()?->hasAnyRole(['Company Admin', 'Manager', 'Super Admin']), 403);

        return view('tenant.users.index', [
            'users' => User::query()->latest()->paginate(12),
            'roles' => ['Company Admin', 'Manager', 'Employee'],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()?->hasAnyRole(['Company Admin', 'Super Admin']), 403);

        AuthorizationDefaults::ensure();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,NULL,id,tenant_id,'.tenant()->id],
            'job_title' => ['nullable', 'string', 'max:255'],
            'role' => ['required', 'in:Company Admin,Manager,Employee'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::query()->create([
            'tenant_id' => tenant()->id,
            'name' => $data['name'],
            'email' => $data['email'],
            'job_title' => $data['job_title'] ?? null,
            'password' => Hash::make($data['password']),
        ]);

        $user->assignRole($data['role']);

        return back()->with('status', 'Tenant user created.');
    }
}
