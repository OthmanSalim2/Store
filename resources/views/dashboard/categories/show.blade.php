@extends('layouts.dashboard')

@section('title', $category->name)

@section('breadceumb')
    @parent
    <li class="breadcrumb-item active">Starter Page</li>
    <li class="breadcrumb-item active">{{ $category->name }}</li>
@endsection

@section('content')
    <table class="table">
        <thead>
            <tr>
                <th></th>
                <th>Name</th>
                <th>Store</th>
                <th>Status</th>
                <th>Created At</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @php
                $products = $category
                    ->products()
                    ->with('store')
                    ->latest()
                    ->paginate(5);
            @endphp
            @forelse($products as $product)
                <tr>
                    <td><img height="60px" src="{{ asset('storage/' . $product->image) }}" alt="" /></td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->store->name }}</td>
                    <td>{{ $product->status }}</td>
                    <td>{{ $product->created_at }}</td>
                </tr>
            @empty
                <td colspan="5" class="alert alert-danger" role="alert">
                    Products Not Definded
                </td>
            @endforelse
        </tbody>
    </table>

    {{ $products->links() }}


@endsection
