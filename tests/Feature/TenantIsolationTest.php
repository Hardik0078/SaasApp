<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class TenantIsolationTest extends TestCase
{
    use RefreshDatabase;

    public function test_tenant_dashboard_cannot_see_another_tenants_project(): void
    {
        $role = Role::findOrCreate('Company Admin', 'web');

        $tenantA = Tenant::query()->create([
            'id' => (string) Str::uuid(),
            'name' => 'Tenant A',
            'slug' => 'tenant-a',
            'status' => 'active',
            'billing_email' => 'billing-a@example.com',
        ]);

        $tenantB = Tenant::query()->create([
            'id' => (string) Str::uuid(),
            'name' => 'Tenant B',
            'slug' => 'tenant-b',
            'status' => 'active',
            'billing_email' => 'billing-b@example.com',
        ]);

        $userA = User::factory()->create([
            'tenant_id' => $tenantA->id,
            'email' => 'admin-a@example.com',
        ]);
        $userA->assignRole($role);

        Project::query()->create([
            'tenant_id' => $tenantB->id,
            'name' => 'Hidden Project',
            'status' => 'active',
        ]);

        $this->actingAs($userA)
            ->get(route('tenant.projects.index', $tenantA))
            ->assertOk()
            ->assertDontSee('Hidden Project');
    }
}
