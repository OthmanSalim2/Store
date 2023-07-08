<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

class ProductController extends Controller
{



    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        // here possible writing view-any or viewAny
        // the second argument it's Product::class here laravel automatic to  policy name it ProductPolicy if committed of naming
        // if not commit of naming will go to AuthenticationServiceProvider it's contain on $policies here identify manual
        // view-any it's name of function in ProductPolicy
        $this->authorize('view-any', Product::class);

        $request = request();
        $products = Product::with(['category', 'store'])->paginate();
        // SELECT * FROM products;
        //SELECT * FROM categories WHERE id IN (...here receive category_id from products)
        //SELECT * FROM stores WHERE id IN (...here receive store_id from products)

        return view('dashboard.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Product::class);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Product::class);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $product = Product::findOrFail($id);
        // here pass $product to authorize function laravel here automatic understand to passed argument type it Product and has ProductPolicy
        $this->authorize('view', $product);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {

        $product = Product::findOrFail($id);

        $this->authorize('update', $product);

        $tags = implode(',', $product->tags()->pluck('name')->toArray());

        return view('dashboard.products.edit', compact('product', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {

        $this->authorize('update', $product);


        $product->update($request->except('tags'));

        $tags = explode(',', $request->post('tags'));

        $tag_ids = [];

        $tags_saved = Tag::all();

        foreach ($tags as $t_name) {

            $slug = Str::slug($t_name);
            $tag = $tags_saved->where('slug', '=', $slug)->first();

            if (!$tag) {
                $tag = Tag::create([
                    'name' => $t_name,
                    'slug' => $slug,
                ]);
            }

            $tag_ids[] = $tag->id;
        }

        /* sync it's use just to many to many relation, and work ids if were found in tag table don't work anything
        but if were not found will add to tag table and if any tag not found in ids will remove this tag*/
        $product->tags()->sync($tag_ids);

        return redirect()->route('dashboard.products.index')
            ->with('success', 'Product updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);

        $this->authorize('delete', $product);

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

        $this->authorize('restore', $product);

        $product->restore();

        return redirect()->route('dashboard.products.trash')->with('success', 'Restored Product');
    }

    public function foreDelete(Request $request, $id)
    {

        $product = Product::findOrFail($id);

        // authorize automatic make throw for exception and laravel make handle for this exception
        // put if I need throw exception for me use try catch
        $this->authorize('fore-delete', $product);

        $product->foreDelete();

        // Here Put command for delete the image from disk.

        return redirect()->route('dashboard.products.trash')->with('success', 'Product deleted forever!');
    }
}
