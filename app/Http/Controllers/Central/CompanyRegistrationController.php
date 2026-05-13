<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Models\Tenant;
use App\Models\User;
use App\Services\BillingService;
use App\Support\AuthorizationDefaults;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class CompanyRegistrationController extends Controller
{
    public function create(): View
    {
        return view('central.register-company', [
            'plans' => SubscriptionPlan::query()->where('is_active', true)->get(),
        ]);
    }

    public function store(Request $request, BillingService $billingService): RedirectResponse
    {
        AuthorizationDefaults::ensure();

        $data = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'company_slug' => ['required', 'alpha_dash', 'max:255', 'unique:tenants,slug'],
            'billing_email' => ['required', 'email', 'max:255'],
            'admin_name' => ['required', 'string', 'max:255'],
            'admin_email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'plan_id' => ['nullable', 'exists:subscription_plans,id'],
        ]);

        $tenant = DB::transaction(function () use ($data, $billingService) {
            $tenant = Tenant::query()->create([
                'id' => (string) Str::uuid(),
                'name' => $data['company_name'],
                'slug' => $data['company_slug'],
                'billing_email' => $data['billing_email'],
                'status' => 'trial',
                'trial_ends_at' => now()->addDays(14),
            ]);

            $admin = User::query()->create([
                'tenant_id' => $tenant->id,
                'name' => $data['admin_name'],
                'email' => $data['admin_email'],
                'password' => Hash::make($data['password']),
                'job_title' => 'Company Admin',
            ]);

            $admin->assignRole('Company Admin');

            if (! empty($data['plan_id'])) {
                $plan = SubscriptionPlan::query()->findOrFail($data['plan_id']);
                $billingService->subscribe($tenant, $plan, $plan->billing_period);
            }

            return $tenant;
        });

        return redirect()->route('tenant.login', ['tenant' => $tenant])
            ->with('status', 'Company created successfully. Sign in to continue.');
    }
}
