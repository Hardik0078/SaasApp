<?php

namespace Tests\Unit;

use App\Models\SubscriptionPlan;
use App\Models\Tenant;
use App\Services\BillingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class BillingServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_subscribe_creates_subscription_and_invoice(): void
    {
        $tenant = Tenant::query()->create([
            'id' => (string) Str::uuid(),
            'name' => 'Billing Co',
            'slug' => 'billing-co',
            'status' => 'trial',
            'billing_email' => 'billing@example.com',
        ]);

        $plan = SubscriptionPlan::query()->create([
            'name' => 'Starter',
            'slug' => 'starter',
            'billing_period' => 'monthly',
            'price' => 49,
            'currency' => 'USD',
            'is_active' => true,
        ]);

        $subscription = app(BillingService::class)->subscribe($tenant, $plan, 'monthly');

        $this->assertDatabaseHas('company_subscriptions', [
            'id' => $subscription->id,
            'tenant_id' => $tenant->id,
            'status' => 'active',
        ]);

        $this->assertDatabaseHas('billing_invoices', [
            'tenant_id' => $tenant->id,
            'amount' => 49.00,
        ]);
    }
}
