<?php

use App\Http\Controllers\Dashboard\CategoriesController;
use App\Http\Controllers\Dashboard\ProductController;
use App\Http\Controllers\Dashboard\ProfileController as DashboardProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;



// Route::get('/dashboard', [DashboardController::class, 'index'])
//     ->middleware(['auth', 'verified'])->name('dashboard');


// Route::resource('dashboard/categories', CategoriesController::class)
//     ->middleware(['auth']);


// Route::resource('dashboard/products', CategoriesController::class)
//     ->middleware(['auth']);


/*

other method to define the route group

Route::group([
    'middleware => ['auth']
],function() {
    Route::resource('categories', CategoriesController::class);

        Route::resource('products', CategoriesController::class);
});

*/

Route::middleware(['auth'])->as('dashboard.')->prefix('dashboard')->group(
    function () {

        Route::get('/', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('profile', [DashboardProfileController::class, 'edit'])
            ->name('profile.edit');
        Route::patch('profile', [DashboardProfileController::class, 'update'])
            ->name('profile.update');

        Route::get('categories/trash', [CategoriesController::class, 'trash'])
            ->name('categories.trash');

        Route::put('categories/{category}/restore', [CategoriesController::class, 'restore'])
            ->name('categories.restore');

        Route::get('products/trash', [ProductController::class, 'trash'])
            ->name('products.trash');

        Route::put('products/{product}/restore', [ProductController::class, 'restore'])
            ->name('products.restore');


        Route::delete('categories/{category}/force-delete', [CategoriesController::class, 'forceDelete'])
            ->name('categories.force-delete');

        Route::resource('categories', CategoriesController::class);

        Route::resource('products', ProductController::class);

        // Route::get('categories/{category}', [CategoriesController::class, 'show'])
        //     ->name('categories.trash')
        //     ->where('category', '\d+'); // just receive integer
    }

);
