<?php

namespace App\Models;

use Stancl\Tenancy\Database\Concerns\HasDataColumn;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;

class Tenant extends BaseTenant
{
    use HasDataColumn;
    use HasDomains;

    protected $fillable = [
        'id',
        'name',
        'slug',
        'status',
        'billing_email',
        'trial_ends_at',
        'subscription_ends_at',
        'settings',
        'data',
    ];

    protected function casts(): array
    {
        return [
            'trial_ends_at' => 'datetime',
            'subscription_ends_at' => 'datetime',
            'settings' => 'array',
            'data' => 'array',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'name',
            'slug',
            'status',
            'billing_email',
            'trial_ends_at',
            'subscription_ends_at',
            'settings',
            'created_at',
            'updated_at',
        ];
    }
}
