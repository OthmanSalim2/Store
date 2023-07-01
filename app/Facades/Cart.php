<?php

namespace App\Facades;

use App\Repositories\Cart\CartRepository;
use Illuminate\Support\Facades\Facade;

class Cart extends Facade
{

    // This's the method required when extends from facade class
    public static function getFacadeAccessor()
    {
        return CartRepository::class;
    }
}
