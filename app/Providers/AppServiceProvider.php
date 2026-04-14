<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\Gate::define('view-dashboard', fn($user) => true);
        \Illuminate\Support\Facades\Gate::define('view-profile', fn($user) => true);
        \Illuminate\Support\Facades\Gate::define('view-teachers', fn($user) => true);
        \Illuminate\Support\Facades\Gate::define('view-subjects', fn($user) => true);
        \Illuminate\Support\Facades\Gate::define('view-courses', fn($user) => true);
        \Illuminate\Support\Facades\Gate::define('view-social', fn($user) => true);
        \Illuminate\Support\Facades\Gate::define('view-reports', fn($user) => true);
        \Illuminate\Support\Facades\Gate::define('view-settings', fn($user) => true);
    }
}
