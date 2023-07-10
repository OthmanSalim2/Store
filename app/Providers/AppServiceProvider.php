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

        // when be environment production mean on the server if was local mean on my device
        if (App::environment('production')) {
            // path.public represent app->make('path.public') when click ctrl+click mouse
            $this->app->singleton('path.public', function () {
                // here base_path return the main folder
                // here I say go to main folder after that enter to public_html folder or path of public_html
                return base_path('public_html');
            });
        }

        /*
        public_path() this method always return path of public folder,
         possible here happen change at folder name so the best use up code direct follow App:environment
         */
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
