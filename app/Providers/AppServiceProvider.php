<?php

namespace App\Providers;

use App\Contracts\SchoolLogoStorage;
use App\Services\DatabaseSchoolLogoStorage;
use App\Tenancy\TenantContext;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->scoped(TenantContext::class, fn () => new TenantContext);
        $this->app->bind(SchoolLogoStorage::class, DatabaseSchoolLogoStorage::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
