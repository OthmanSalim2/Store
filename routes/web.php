<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Front\CartController;
use App\Http\Controllers\Front\CheckoutController;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\ProductsController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index'])
    ->name('front.home');

Route::get('/product', [ProductsController::class, 'index'])
    ->name('products.index');
Route::get('/product/{product:slug?}', [ProductsController::class, 'show'])
    ->name('products.show');

Route::post('paypal/webhook', function () {
    echo "Welcome in my page";
});

Route::resource('cart', CartController::class);

Route::get('checkout', [CheckoutController::class, 'create'])->name('checkout');
Route::post('checkout', [CheckoutController::class, 'store']);

require __DIR__ . '/profile.php';

require __DIR__ . '/auth.php';

require __DIR__ . '/dashboard.php';
