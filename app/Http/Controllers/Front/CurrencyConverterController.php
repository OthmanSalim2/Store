<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Services\CurrencyConverter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class CurrencyConverterController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'currency_code' => ['required', 'string', 'max:3']
        ]);


        $baseCurrencyCode = config('app.currency');
        $currencyCode = $request->post('currency_code');

        $cacheKey = 'currency_rate_' . $currencyCode;

        // Here cache be for all users mean all users see it
        $rate = Cache::get($cacheKey, 0);

        if (!$rate) {
            $converter = app('currency.converter');
            // other way for bind currency converter from App Service Provider
            // $converter = App::make('currency.converter');
            $rate = $converter->convert($baseCurrencyCode, $currencyCode);

            Cache::put($cacheKey, $rate, now()->addMinutes(60));
        }

        // here session it's special of user
        Session::put('currency_code', $currencyCode);

        return redirect()->back();
    }
}
