<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\BillingInvoice;
use App\Models\CompanySubscription;
use App\Models\SubscriptionPlan;
use App\Services\BillingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    public function show(): View
    {
        abort_unless(auth()->user()?->hasAnyRole(['Company Admin', 'Super Admin']), 403);

        return view('tenant.subscription.show', [
            'plans' => SubscriptionPlan::query()->where('is_active', true)->get(),
            'subscription' => CompanySubscription::query()->with('plan')->latest()->first(),
            'invoices' => BillingInvoice::query()->with('subscription.plan')->latest()->get(),
            'stripeConfigured' => filled(Config::get('services.stripe.secret')) && filled(Config::get('services.stripe.key')),
        ]);
    }

    public function update(Request $request, BillingService $billingService): RedirectResponse
    {
        abort_unless(auth()->user()?->hasAnyRole(['Company Admin', 'Super Admin']), 403);

        $data = $request->validate([
            'plan_id' => ['required', 'exists:subscription_plans,id'],
            'billing_period' => ['required', 'in:monthly,yearly'],
        ]);

        $plan = SubscriptionPlan::query()->findOrFail($data['plan_id']);
        $billingService->subscribe(tenant(), $plan, $data['billing_period'], [
            'provider' => 'manual',
        ]);

        return back()->with('status', 'Subscription updated.');
    }

    public function checkout(Request $request, BillingService $billingService): RedirectResponse
    {
        abort_unless(auth()->user()?->hasAnyRole(['Company Admin', 'Super Admin']), 403);

        $data = $request->validate([
            'plan_id' => ['required', 'exists:subscription_plans,id'],
            'billing_period' => ['required', 'in:monthly,yearly'],
        ]);

        $plan = SubscriptionPlan::query()->findOrFail($data['plan_id']);
        $checkoutUrl = $billingService->createCheckoutSession(tenant(), $plan, $data['billing_period']);

        return redirect()->away($checkoutUrl);
    }

    public function success(Request $request, BillingService $billingService): RedirectResponse
    {
        abort_unless(auth()->user()?->hasAnyRole(['Company Admin', 'Super Admin']), 403);

        $request->validate([
            'session_id' => ['required', 'string'],
        ]);

        $billingService->activateFromStripeSuccess(tenant(), (string) $request->string('session_id'));

        return redirect()->route('tenant.subscription.show', ['tenant' => tenant()])
            ->with('status', 'Stripe subscription activated and invoice generated.');
    }
}
