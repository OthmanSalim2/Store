<?php

namespace App\Models;

use App\Observers\CartObserver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use App\Models\Product;

class Cart extends Model
{
    use HasFactory, Notifiable;

    public $incrementing = false;

    protected $fillable = [
        'cookie_id', 'id', 'user_id', 'product_id', 'quantity', 'options'
    ];

    // Events(observers)
    // creating, created, updating, updated, saving, saved
    // deleting, deleted, restoring, restored, retrieved

    public static function booted()
    {
        static::observe(CartObserver::class);

        static::addGlobalScope('cookie', function (Builder $builder) {
            $builder->where('cookie_id', '=', Cart::getCookieId());
        });

        // static::creating(function (Cart $cart) {
        //     $cart->id = Str::uuid();
        // });
    }

    public function user()
    {
        return $this->belongsTo(User::class)
            ->withDefault([
                'name' => 'Anonymous'
            ]);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id')
            ->withDefault([
                'name' => 'new product'
            ]);
    }

    public static function getCookieId()
    {
        $cookie_id = Cookie::get('cart_id');

        if (!$cookie_id) {
            $cookie_id = Str::uuid();
            Cookie::queue('cart_id', $cookie_id, 30 * 24 * 30);
        }

        return $cookie_id;
    }
}
