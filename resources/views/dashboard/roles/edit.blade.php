@extends('layouts.dashboard')

@section('title', 'Update Role')

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

    <form action="{{ route('dashboard.roles.update', $role->id) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @include('dashboard.roles._form', [
            'button_label' => 'Update',
        ])

    </form>

@endsection
