@if ($errors->any())
    <div class="alert alert-danger">
        <h3>Error Occured</h3>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="form-group">

    <x-form.input label="Product Name" role="input" class="form-control-lg" type="text" name="name"
        :value="$product->name" />
    {{-- other way <x-form.input $type="text" name="name" value="{{$category->name}}" /> --}}
</div>

<div class="form-group">
    <label for="">Category</label>

    <select name="product_id" class="form-control form-select">
        <option value="">Primary Category</option>
        @foreach (App\Models\Category::all() as $category)
            <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>
        @endforeach
    </select>

</div>

<div class="form-group">

    <x-form.textarea label="Product Description" name="description" :value="$product->description" />


</div>

<div class="form-group">

    <x-form.input label="Product Image" type="file" name="image" :value="$product->image" accept="image/*" />
    @if ($category->image)
        <img style="margin-top: 10px;" height="60px" src="{{ asset('storage/' . $product->image) }}" alt="" />
    @endif
</div>

<div class="form-group">

    <x-form.input label="Price" role="input" class="form-control-lg" type="text" name="prcie"
        :value="$product->price" />
</div>

<div class="form-group">

    <x-form.input label="Compare Price" role="input" class="form-control-lg" type="text" name="compare_price"
        :value="$product->compare_price" />
</div>

<div class="form-group">

    <x-form.input label="Tags" :value="$tags" name="tags" />
</div>

<div class="form-group">
    <x-form.label id="Product_Status">Status</x-form.label>
    <div>

        <x-form.radio type="radio" name="status" :checked="$product->status" :options="['active' => 'Active', 'archived' => 'Archived', 'draft' => 'Draft']" />

    </div>
</div>

<div class="form-group">
    <button type="submit" class="btn btn-primary"> {{ $button_label ?? 'Save' }} </button>
</div>


@push('styles')
    <link href="{{ asset('css/tagify.css') }}" rel="stylesheet" type="text/css" />
@endpush

@push('scripts')
    <script src="{{ asset('js/tagify.min.js') }}"></script>
    <script src="{{ asset('js/tagify.polyfills.min.js') }}"></script>
    <script>
        var inputElm = document.querySelector('[name="tags"]'),
            tagify = new Tagify(inputElm);
    </script>
@endpush
