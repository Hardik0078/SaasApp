<?php

namespace Tests\Feature;

use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CompanyRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_company_registration_creates_tenant_and_admin(): void
    {
        Role::findOrCreate('Company Admin', 'web');

        $response = $this->post(route('companies.store'), [
            'company_name' => 'Acme Inc',
            'company_slug' => 'acme',
            'billing_email' => 'billing@acme.test',
            'admin_name' => 'Alice Admin',
            'admin_email' => 'alice@acme.test',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect(route('tenant.login', Tenant::query()->first()));

        $this->assertDatabaseHas('tenants', [
            'name' => 'Acme Inc',
            'slug' => 'acme',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'alice@acme.test',
            'tenant_id' => Tenant::query()->first()->id,
        ]);
    }
}
