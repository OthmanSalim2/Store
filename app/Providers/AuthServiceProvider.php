<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Product' => 'App\Policies\ModelPolicy',
        // 'App\Models\Role' => 'App\Policies\ModelPolicy',
        // 'App\Models\Admin' => 'App\Policies\ModelPolicy',
    ];

    public function register()
    {
        // this code if register function in this class not work
        parent::register();

        // what's the different between bind and instance, bind:  pass it callback function and if I need it directly return  ,
        // instance pass it variable

        $this->app->bind('abilities', function () {
            return include base_path('data/abilities.php');
        });
    }

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // method before executed before any method mean before define
        Gate::before(function ($user, $ability) {
            if ($user->super_admin) {
                return true;
            }
        });

        // categories.view it's name of authorization
        // Class Gate it's use in authorization subject
        // $user here represent authentication user if normal user or admin that be by user is login

        foreach ($this->app->make('abilities') as $code => $label) {
            Gate::define($code, function ($user) use ($code) {
                return $user->hasAbilities($code);
            });
        }
    }
}
