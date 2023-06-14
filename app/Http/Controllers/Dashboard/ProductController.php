<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $request = request();
        $products = Product::filter($request->query())->paginate();

        return view('dashboard.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $products = Product::findOrFail();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('dashboard.products.index')->with('success', 'Product deleted');
    }

    public function trash()
    {
        $products = Product::onlyTrashed()->paginate();

        return view('dashboard.product.trash', compact('products'));
    }

    public function restore(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->restore();

        return redirect()->route('dashboard.products.trash')->with('success', 'Restored Product');
    }

    public function foreDelete(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->foreDelete();

        // Here Put command for delete the image from disk.

        return redirect()->route('dashboard.products.trash')->with('success', 'Product deleted forever!');
    }
}
