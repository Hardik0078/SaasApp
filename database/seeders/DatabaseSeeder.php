<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Support\AuthorizationDefaults;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        AuthorizationDefaults::ensure();

        SubscriptionPlan::query()->updateOrCreate(
            ['slug' => 'starter-monthly'],
            [
                'name' => 'Starter',
                'billing_period' => 'monthly',
                'price' => 49,
                'currency' => 'USD',
                'project_limit' => 10,
                'user_limit' => 15,
                'features' => ['Projects', 'Tasks', 'Comments', 'Notifications'],
                'is_active' => true,
                'stripe_price_id' => 'price_1TWHjfPX36Io88KKgiPbkdBC',
            ],
        );

        SubscriptionPlan::query()->updateOrCreate(
            ['slug' => 'growth-yearly'],
            [
                'name' => 'Growth',
                'billing_period' => 'yearly',
                'price' => 499,
                'currency' => 'USD',
                'project_limit' => 100,
                'user_limit' => 150,
                'features' => ['Projects', 'Tasks', 'Attachments', 'API', 'Audit logs'],
                'is_active' => true,
                'stripe_price_id' => 'price_1TWFaLPX36Io88KKjSnUCNcd',
            ],
        );

        $superAdmin = User::query()->updateOrCreate(
            [
                'tenant_id' => null,
                'email' => 'admin@saasapp.test',
            ],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
            ],
        );

        $superAdmin->syncRoles(['Super Admin']);



    }
}
