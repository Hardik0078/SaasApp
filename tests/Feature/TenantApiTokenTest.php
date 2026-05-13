<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class TenantApiTokenTest extends TestCase
{
    use RefreshDatabase;

    public function test_tenant_user_can_create_sanctum_token(): void
    {
        Role::findOrCreate('Manager', 'web');

        $tenant = Tenant::query()->create([
            'id' => (string) Str::uuid(),
            'name' => 'Acme',
            'slug' => 'acme',
            'status' => 'active',
            'billing_email' => 'billing@acme.test',
        ]);

        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'email' => 'manager@acme.test',
            'password' => Hash::make('password'),
        ]);
        $user->assignRole('Manager');

        $this->postJson(route('api.tokens.store', ['tenant' => $tenant]), [
            'email' => 'manager@acme.test',
            'password' => 'password',
        ])->assertCreated()
            ->assertJsonStructure(['token', 'user', 'tenant']);
    }
}
