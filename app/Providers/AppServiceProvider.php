<?php

namespace App\Providers;

use App\Services\CurrencyConverter;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind('currency.converter', function () {
            return new CurrencyConverter(config('services.currency_converter.api-key'));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {


        // to display the current language or current local
        // App::currentLocale();
        // config('app.locale')

        // this command will delete data word and return felids inside curly braces
        // JsonResource::withoutWrapping();

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
