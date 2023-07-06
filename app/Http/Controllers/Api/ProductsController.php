<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __construct()
    {
        // apply middleware for each function except index, show function
        // auth:sanctum mean guard:sanctum
        $this->middleware('auth:sanctum')->except('index', 'show');
    }

    public function index(Request $request)
    {
        $products = Product::filter($request->query())
            // here I determine what's that will return from relation just id, name from category relation
            ->with('category:id,name', 'store:id,name', 'tags:id,name')
            ->paginate();

        // this way to return products of course will return collection
        return ProductResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'category_id' => ['required', 'string', 'exists:categories,id'],
            'status' => 'in:active,inactive',
            'price' => ['required', 'numeric', 'min:0'],
            //gt = greater than, Here gt:price mean value of compare_price greater than value of price
            'compare_price' => ['nullable', 'numeric', 'gt:price'],
        ]);

        $user = $request->user();
        // Here speak is he authorization for create process
        // here must not commit of name as product.create possible put product/create or any name
        if (!$user->tokenCan('product.create')) {
            return response()->json([
                'message' => 'Not Allowed',
            ], 403);
        }

        $product = Product::create($request->all());

        // must determine clearly response 201
        return Response::json($product, 201, [
            'Location' => route('products.show', $product->id)
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {

        // this's the new shape for display data
        return new ProductResource($product);

        // with function use with model directly mean Product::with(....) and use if I need return collection
        // load function use with the variable refer to model mean $product->load(....) and use if I need return single model
        //  using with to display felids from anther table by relation because it's Query Builder and can convert to json but
        // not the best choice, best choice use load()
        // and must to be argument type it string
        // return $product->load('category:id,name', 'store:id,name', 'tags:id,name');
    }

    /*
        // this's another way to show the product
        public function show($id) {
            return Product:findOrFail($id);
        }
    */

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            // here sometimes mean if not found this's mean not requesting for updating and sometimes be with required in api
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'category_id' => ['sometimes', 'required', 'string', 'exists:categories,id'],
            'status' => 'in:active,inactive',
            'price' => ['sometimes', 'required', 'numeric', 'min:0'],
            //gt = greater than, Here gt:price mean value of compare_price greater than value of price
            'compare_price' => ['nullable', 'numeric', 'gt:price'],
        ]);


        $user = $request->user();
        // Here speak is he authorization for update process
        if (!$user->tokenCan('product.update')) {
            return response()->json([
                'message' => 'Not Allowed',
            ], 403);
        }

        $product->update($request->all());

        // must determine clearly response 201
        return Response::json($product);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $user = Auth::guard('sanctum')->user();
        // Here speak is he authorization for delete process
        if (!$user->tokenCan('product.delete')) {
            return response()->json([
                'message' => 'Not Allowed',
            ], 403);
        }

        Product::destroy($id);

        return [
            'message' => 'Product deleted successfully',
        ];

        // other way
        // return response()->json([
        //     'message' => 'Product deleted successfully',
        // ], 200);
    }
}
