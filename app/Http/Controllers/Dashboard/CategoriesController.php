<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        // here say if was user authentication not with him the authorization to categories view
        // possible other way !Gate::allows()
        // if (Gate::denies('categories.view')) {
        if (!Gate::allows('categories.view')) {
            // here possible make redirect for specific route or any action
            abort(403);
        }

        $request = request();
        $query = Category::query();


        //other way to compare
        // $query->whereStatus($status); // here put name of field after where

        $categories = Category::with('parent')
            // ->select('categories.*')
            // ->selectRaw('(SELECT COUNT(*) FROM products WHERE status = 'active' AND category_id = categories.id) as products_count')
            //other way to select count products
            ->withCount([
                'products' => function ($query) {
                    $query->where('status', '=', 'active');
                }
            ])
            ->filter($request->query())
            ->orderBy('categories.name')
            ->paginate();

        // other way
        // $categories = Category::LeftJoin('categories as parent', 'parent.id', '=', 'categories.parent_id')
        //     ->select([
        //         'categories.*',
        //         'parent.name as parent_name'
        //     ])
        //     ->filter($request->query())
        //     ->orderBy('categories.name')
        //     ->paginate();

        return view('dashboard.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        /* this's If you would like to attempt to authorize an action and automatically throw an AuthorizationException
         if the user is not allowed to perform the given action and automatically return abort 403
         */

        // here There is no need to use authorize because I make it in CategoryRequest
        // Gate::authorize('categories.create');

        // other way
        // if (Gate::denies('categories.view')) {
        //     abort(403);
        // }

        $parents = Category::all();
        $category = new Category;
        return view('dashboard.categories.create', compact('parents', 'category'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        // Validate request

        // $request->validate(Category::rules());

        // Request merge
        $request->merge([
            'slug' => Str::slug($request->post('name'))
        ]);

        $data = $request->except('image');
        $data['image'] = $this->uploadImage($request);

        // Mass assignment
        $category = Category::create($data);

        // PRG
        return redirect()->route('dashboard.categories.index')
            ->with('success', 'Category created');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return view('dashboard.categories.show', [
            'category' => $category
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        // Gate::authorize('categories.update');

        try {
            $category = Category::findOrFail($id);
        } catch (Exception $e) {
            return redirect()->route('dashboard.categories.index')
                ->with('info', 'Record not found!');
        }

        // SELECT * FROM category where id <> $id
        // AND (parent_id is null or parent_id <> $id);
        $parents = Category::where('id', '<>', $id)
            ->where(function ($query) use ($id) {
                $query->whereNull('parent_id');
                //until don't give me children
                $query->orWhere('parent_id', '<>', $id);
            })->get();

        return view('dashboard.categories.edit', compact('category', 'parents'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, string $id)
    {
        // validate request

        // $request->validate(Category::rules($id));

        $old_image = $request->image;

        $data = $request->except('image');

        $new_image = $this->uploadImage($request); // this is mean if was inside it value

        if ($new_image) {
            $data['image'] = $new_image;
        }

        if ($old_image && $new_image) {
            Storage::disk('public')->delete($old_image);
        }

        try {
            $category = Category::findOrFail($id);
        } catch (Exception $e) {
            return redirect()->route('dashboard.categories.index')->with('info', 'The Record not found!');
        }

        $category->update($data); // this is update the data and save
        // there other way $category->fill($request->all())->save();

        return redirect()->route('dashboard.categories.index')->with('success', 'Category updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        Gate::authorize('categories.delete');

        $category = Category::findOrFail($id);
        $category->delete();

        // other way for delete the category
        // Category::destroy($id);
        // other way for delete the category Category::where('id', '=', $id)->delete();

        return redirect()->route('dashboard.categories.index')->with('delete', 'Category deleted');
    }

    protected function uploadImage(Request $request)
    {
        if (!$request->hasFile('image')) {
            return;
        }

        $file = $request->file('image'); // Upload File Object
        $path = $file->store('uploads', [
            'disk' => 'public'
        ]);

        return $path;
    }

    public function trash()
    {
        $categories = Category::onlyTrashed()->paginate();
        return view('dashboard.categories.trash', compact('categories'));
    }

    public function restore(Request $request, $id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->restore();

        return redirect()->route('dashboard.categories.trash')
            ->with('success', 'Category restored!');
    }

    public function forceDelete(Request $request, $id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->forceDelete();

        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }


        return redirect()->route('dashboard.categories.trash')
            ->with('success', 'Category deleted forever!');
    }
}
