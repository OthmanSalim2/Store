@extends('layouts.dashboard')

@section('title', 'Categories Trashed')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item">Product</li>
    <li class="breadcrumb-item active">Trash</li>
@endsection

@section('content')

    <div class="mb-5">
        <a href="{{ route('dashboard.products.index') }}" class="btn btn-sm btn-outline-primary">Back</a>
    </div>

    <x-alert type="success" />
    <x-alert type="info" />

    <form action="{{ URL::current() }}" method="get" class="d-flex justify-content-between mb-4">
        <x-form.input type="text" name="name" placeholder="Name" class="mx-2" :value="request('name')" />

        <select name="status" class="form-control mx-2">
            <option value="">All</option>
            <option value="active" @selected(request('status') == 'active')>Active</option>
            <option value="archived" @selected(request('status') == 'archived')>Archived</option>
        </select>

        <button class="btn btn-dark">Filter</button>

    </form>

    <div class="mb-3 ml-3">
        <a class="btn btn-sm btn-outline-primary" href="{{ route('dashboard.products.create') }}">Create</a>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Image</th>
                <th>ID</th>
                <th>Name</th>
                <th>Status</th>
                <th>Deleted At</th>
                <th colspan="2"></th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
                <tr>
                    <td><img height="60px" src="{{ asset('storage/' . $product->image) }}" alt="" /></td>
                    <td>{{ $product->id }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->status }}</td>
                    <td>{{ $product->deleted_at }}</td>

                    <td>
                        <form action="{{ route('dashboard.products.restore', $product->id) }}" method="post">
                            @csrf
                            {{-- Form Method Spoofing  --}}
                            @method('PUT')
                            <button class="btn btn-sm btn-outline-info" type="submit">Restore</button>
                        </form>
                    </td>
                    <td>
                        <form action="{{ route('dashboard.products.force-delete', $product->id) }}" method="post">
                            @csrf
                            {{-- Form Method Spoofing  --}}
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <td colspan="7" role="alert">
                    Products Not Definded
                </td>
            @endforelse
        </tbody>
    </table>

    {{ $products->withQueryString()->links() }}

@endsection
