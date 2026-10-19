<?php

namespace App\Models\Concerns;

use App\Tenancy\TenantContext;
use Illuminate\Database\Eloquent\Builder;

trait BelongsToSchool
{
    public static function bootBelongsToSchool(): void
    {
        static::addGlobalScope('school', function (Builder $query) {
            $query->where($query->getModel()->qualifyColumn('school_id'), app(TenantContext::class)->id());
        });
        static::creating(function ($model) {
            $schoolId = app(TenantContext::class)->id();
            if (isset($model->school_id) && (int) $model->school_id !== $schoolId) {
                throw new \LogicException('Cross-school write refused.');
            }
            $model->school_id = $schoolId;
        });
    }
}
