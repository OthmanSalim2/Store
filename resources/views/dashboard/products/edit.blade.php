@extends('layouts.dashboard')

@section('title', 'Update Category')

@section('breadceumb')
    @parent
    <li class="breadcrumb-item active">Starter Page</li>
@endsection

@section('content')

    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('dashboard.categories.update', $category->id) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @include('dashboard.categories._form', [
            'button_label' => 'Update',
        ])

    </form>

@endsection
