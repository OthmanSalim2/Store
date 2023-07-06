<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class SetAppLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        // $locale = request('locale', Cookie::get('locale', config('app.locale')));
        $locale = $request->route('locale');
        App::setLocale($locale);

        // here put the parameter and put default value if user didn't put it
        URL::defaults([
            'locale' => $locale
        ]);

        // here current() it's return object from route class
        // I here say to laravel forget locale parameter mean return parameters to controller in the order
        Route::current()->forgetParameter('locale');

        // Cookie::queue('locale', $locale, 60 * 24 * 365);
        return $next($request);
    }
}
