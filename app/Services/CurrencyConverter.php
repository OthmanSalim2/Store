<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CurrencyConverter
{

    private $apiKey;

    protected $baseUrl = "https://api.freecurrencyapi.com/v1";

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }
    // https://api.freecurrencyapi.com/v1/latest?apikey=yVWAFLqqHvXqKbLFHcmOkFE4fg10z9mvZDNfrodG&currencies=

    public function convert(string $from, string $to, float $amount = 1): float
    {
        $q = "{$from}_{$to}";

        // here response will return of json
        // I here determine the base url that I will work on it
        // latest came from url
        $response = Http::baseUrl($this->baseUrl)->get('/latest', [
            // 'from' => $from,
            // 'to' => $to,
            // apikey here must to be the same key name that return from api link
            'apikey' => $this->apiKey,


        ]);
        // https://api.freecurrencyapi.com/v1/latest?apikey=yVWAFLqqHvXqKbLFHcmOkFE4fg10z9mvZDNfrodG


        // here convert from json to object
        $result = $response->json();
        // dd($result['data']['USD']);

        return $result['data'][$to] * $amount;
    }
}
