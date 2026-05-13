<?php

namespace App\Models\Concerns;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToTenant
{
    public static function bootBelongsToTenant(): void
    {
        static::addGlobalScope('tenant', function (Builder $builder): void {
            if (! tenant()) {
                return;
            }

            $builder->where($builder->qualifyColumn('tenant_id'), tenant()->getTenantKey());
        });

        static::creating(function (Model $model): void {
            if (tenant() && empty($model->tenant_id)) {
                $model->tenant_id = tenant()->getTenantKey();
            }
        });
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
