<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Facades\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\Cast\Object_;
use Throwable;

class DeductProductQuantity
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderCreated $event): void
    {
        $order = $event->order;

        try {

            foreach ($order->products as $product) {


                $product->decrement('quantity', $product->order_item_pivot->quantity);

                // Product::where('id', '=', $item->product_id)
                //     ->update([
                //         'quantity' => DB::raw("quantity - {$item->quantity}"),
                //     ]);
            }
        } catch (Throwable $e) {
        }
    }
}
