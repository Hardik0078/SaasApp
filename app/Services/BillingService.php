<?php

namespace App\Services;

use App\Events\SubscriptionExpired;
use App\Models\BillingInvoice;
use App\Models\CompanySubscription;
use App\Models\SubscriptionPlan;
use App\Models\Tenant;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Stripe\StripeClient;

class BillingService
{
    public function subscribe(Tenant $tenant, SubscriptionPlan $plan, string $billingPeriod, array $meta = []): CompanySubscription
    {
        CompanySubscription::query()
            ->where('tenant_id', $tenant->id)
            ->where('status', 'active')
            ->update(['status' => 'replaced']);

        $subscription = CompanySubscription::query()->create([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
            'status' => 'active',
            'billing_period' => $billingPeriod,
            'starts_at' => now(),
            'ends_at' => $billingPeriod === 'yearly' ? now()->addYear() : now()->addMonth(),
            'stripe_customer_id' => data_get($meta, 'stripe_customer_id'),
            'stripe_subscription_id' => data_get($meta, 'stripe_subscription_id'),
            'meta' => array_merge(['provider' => data_get($meta, 'provider', 'manual')], $meta),
        ]);

        $tenant->forceFill([
            'status' => 'active',
            'subscription_ends_at' => $subscription->ends_at,
        ])->save();

        $this->generateInvoice($tenant, $subscription, $plan);

        return $subscription;
    }

    public function generateInvoice(Tenant $tenant, CompanySubscription $subscription, SubscriptionPlan $plan): BillingInvoice
    {
        return BillingInvoice::query()->create([
            'tenant_id' => $tenant->id,
            'company_subscription_id' => $subscription->id,
            'invoice_number' => 'INV-'.strtoupper(Str::random(10)),
            'amount' => $plan->price,
            'currency' => $plan->currency,
            'status' => 'issued',
            'issued_at' => now(),
            'due_at' => now()->addDays(7),
            'meta' => [
                'plan' => $plan->slug,
                'billing_period' => $subscription->billing_period,
                'provider' => data_get($subscription->meta, 'provider', 'manual'),
            ],
        ]);
    }

    public function createCheckoutSession(Tenant $tenant, SubscriptionPlan $plan, string $billingPeriod): string
    {
        $secret = (string) Config::get('services.stripe.secret');

        abort_if($secret === '', 422, 'Stripe secret key is not configured.');
        abort_if(blank($plan->stripe_price_id), 422, 'Stripe price ID is missing for this plan.');

        $stripe = new StripeClient($secret);

        $session = $stripe->checkout->sessions->create([
            'mode' => 'subscription',
            'customer_email' => $tenant->billing_email,
            'line_items' => [[
                'price' => $plan->stripe_price_id,
                'quantity' => 1,
            ]],
            'metadata' => [
                'tenant_id' => $tenant->id,
                'plan_id' => $plan->id,
                'billing_period' => $billingPeriod,
            ],
            'success_url' => route('tenant.subscription.success', ['tenant' => $tenant]).'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('tenant.subscription.show', ['tenant' => $tenant]),
        ]);

        return $session->url;
    }

    public function activateFromStripeSuccess(Tenant $tenant, string $sessionId): CompanySubscription
    {
        $secret = (string) Config::get('services.stripe.secret');

        abort_if($secret === '', 422, 'Stripe secret key is not configured.');

        $stripe = new StripeClient($secret);
        $session = $stripe->checkout->sessions->retrieve($sessionId, []);

        $plan = SubscriptionPlan::query()->findOrFail((int) data_get($session->metadata, 'plan_id'));
        $billingPeriod = (string) data_get($session->metadata, 'billing_period', $plan->billing_period);

        return $this->subscribe($tenant, $plan, $billingPeriod, [
            'provider' => 'stripe',
            'stripe_customer_id' => $session->customer,
            'stripe_subscription_id' => $session->subscription,
            'stripe_checkout_session_id' => $session->id,
        ]);
    }

    public function expireOverdueSubscriptions(): void
    {
        CompanySubscription::query()
            ->where('status', 'active')
            ->whereNotNull('ends_at')
            ->where('ends_at', '<', now())
            ->each(function (CompanySubscription $subscription): void {
                $subscription->update(['status' => 'expired']);
                $subscription->tenant->update(['status' => 'expired']);

                SubscriptionExpired::dispatch($subscription);
            });
    }
}
