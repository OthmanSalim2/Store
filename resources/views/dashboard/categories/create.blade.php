@extends('layouts.dashboard')

@section('title', 'Add Category')

@section('breadceumb')
    @parent
    <li class="breadcrumb-item active">Starter Page</li>
@endsection

@section('content')

    <form action="{{ route('dashboard.categories.store') }}" method="post" enctype="multipart/form-data">
        @csrf

        @include('dashboard.categories._form', [
            'button_label' => 'Create',
        ])

    </form>

@endsection
