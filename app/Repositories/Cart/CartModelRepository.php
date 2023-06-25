<?php

namespace App\Repositories\Cart;

use Illuminate\Support\Collection;
use App\Models\Cart;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class CartModelRepository implements CartRepository
{
    public function get(): Collection
    {
        return Cart::with('product')->get();
    }

    public function add(Product $product, $quantity = 1)
    {
        $item = Cart::where('product_id', '=', $product->id)->first();

        if (!$item) {

            return Cart::create([
                'cookie_id' => cookie(),
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'quantity' => $quantity,
            ]);
        }

        return $item->increment('quantity', $quantity);
    }

    public function update(Product $product, $quantity)
    {

        Cart::where('product_id', '=', $product->id)
            ->update([
                'quantity' => $quantity,
            ]);
    }

    public function empty()
    {
        Cart::query()->destroy();
    }

    public function delete($id)
    {
        Cart::where('product_id', '=', $id)
            ->delete();
    }

    public function total(): float
    {
        return (float) Cart::join('products', 'products.id', 'carts.product_id')
            ->selectRaw('SUM(products.price * carts.quantity) as total')
            ->value('total');
    }
}
