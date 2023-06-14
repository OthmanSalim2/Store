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

<div class="form-group m-2">

    <x-form.input label="Product Name" class="form-control-lg" type="text" name="name" :value="$category->name" />
    {{-- other way <x-form.input $type="text" name="name" value="{{$category->name}}" /> --}}
</div>

<div class="form-group m-2">
    <label for="">Category Parent</label>

    <x-form.select name="parent_id" :parents="$parents" :value="$category->parent_id" />

</div>

<div class="form-group m-2">

    <x-form.textarea label="Category Description" name="description" :value="$category->description" />


</div>

<div class="form-group m-2">

    <x-form.input label="Category Image" type="file" name="image" :value="$category->image" accept="image/*" />
    @if ($category->image)
        <img style="margin-top: 10px;" height="60px" src="{{ asset('storage/' . $category->image) }}" alt="" />
    @endif
</div>

<div class="form-group m-2">
    <x-form.label id="Category_Status">Status</x-form.label>
    <div>

        <x-form.radio type="radio" name="status" :checked="$category->status" :options="['active' => 'Active', 'archived' => 'Archived']" />

    </div>
</div>

<div class="form-group m-2">
    <button type="submit" class="btn btn-primary"> {{ $button_label ?? 'Save' }} </button>
</div>
