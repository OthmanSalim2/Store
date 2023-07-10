@extends('layouts.dashboard')

@section('title', 'Import Products')

@section('breadceumb')
    @parent
    <li class="breadcrumb-item active">Product</li>
@endsection

@section('content')

    <form action="{{ route('dashboard.products.import') }}" method="post" enctype="multipart/form-data">
        @csrf

        <div class="form-group">

            <x-form.input label="Products Count" role="input" class="form-control-lg" type="text" name="count" />
        </div>

        <button type="submit" class="btn btn-primary">Starter Import...</button>

    </form>

@endsection
