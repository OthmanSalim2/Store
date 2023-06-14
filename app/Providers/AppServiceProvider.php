<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Validator;
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

        Paginator::useBootstrapFour();

        Validator::extend(
            'filter',
            function ($attribute, $value, $parameters) {
                if (in_array(strtolower($value), $parameters)) {
                    return false;
                }
                return true;
            },
            'The value is prohibited'
        );
    }
}
