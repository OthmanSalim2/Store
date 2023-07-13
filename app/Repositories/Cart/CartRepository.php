<?php

namespace App\Repositories\Cart;

use App\Models\Product;
use Illuminate\Support\Collection;

interface CartRepository
{

    // For return collection
    public function get(): Collection;

    // For add product in cart
    public function add(Product $product, $quantity = 1);

    // For update quantity of product in cart
    public function update($id, $quantity);

    // This is for clear all product from cart
    public function empty();

    // This is for delete specific product from the cart
    public function delete($id);

    // This is for return sum of products price at the cart
    public function total(): float;
}
