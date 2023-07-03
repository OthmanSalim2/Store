<?php

namespace App\Providers;

use App\Actions\Fortify\AuthenticateUser;
use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\LogoutResponse;
use Laravel\Fortify\Contracts\RegisterResponse;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $request = request();

        if ($request->is('admin/*')) {
            // the dynamic change in fortify file in config folder just if accept for condition
            Config::set('fortify.guard', 'admin');
            Config::set('fortify.passwords', 'admins');
            Config::set('fortify.prefix', 'admin');
            // this's the first way to transfer to admin pages or user pages
            // Config::set('fortify.home', 'admin/dashboard');
        }

        // I here speak for laravel after the user make login what's the response that will return usually the return redirect response
        // this's the second way to transfer to admin pages or user pages
        $this->app->instance(LoginResponse::class, new class implements LoginResponse
        {
            public function toResponse($request)
            {
                // Here guard=>'admin'
                if ($request->user('admin')) {
                    // this will return the user on the page that him try enter to page  if was not found redirect to admin/dashboard
                    return redirect()->intended('admin/dashboard');
                }
                return redirect()->intended('/');
            }
        });

        // $this->app->instance(LogoutResponse::class, new class implements LogoutResponse
        // {
        //     public function toResponse($request)
        //     {
        //         return redirect()->intended('/');
        //     }
        // });

        // $this->app->instance(RegisterResponse::class, new class implements RegisterResponse
        // {
        //     public function toResponse($request)
        //     {
        //         return redirect()->intended('/');
        //     }
        // });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);


        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())) . '|' . $request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        /* this's identify view for registerView of Fortify package
           and without use route I mean the routes of Fortify identified in session
           and this's way to return view for login or register or ...
        */

        // this's way used if I committed of default name for view files for login and register and ...
        if (Config::get('fortify.guard') == 'admin') {
            // I here speak to laravel use the authenticate that I built it if was user use guard admin
            // and the method authenticateUsing I pass to it callback function or callback class this's callback function make authenticate for user and validation process
            // and this's allow for user make login to page by phone or email or username
            Fortify::authenticateUsing([new AuthenticateUser, 'authenticate']);

            Fortify::viewPrefix('auth.');
        } else {
            Fortify::viewPrefix('front.auth.');
        }

        // this's the second way
        // Fortify::loginView('auth.login');
        // Fortify::requestPasswordResetLinkView('auth.forgot-password');
        // // other way to identify view for registerView of Fortify package
        // Fortify::registerView(function () {
        //     return view('auth.register');
        // });

        // this's other way to check the guard and use specific view templates
        // Fortify::loginView(function () {
        //     if (Config::get('fortify.guard') == 'web') {
        //         return view('front.auth.login');
        //     }

        //     return view('auth.login');
        // });
    }
}
