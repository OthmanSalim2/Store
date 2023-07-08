@extends('layouts.dashboard')

@section('title', 'Roles')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Roles</li>
@endsection

@section('content')

    <div class="mb-5">
        {{-- here say is he can make create for role  --}}
        {{-- this's other way --}}
        {{-- @if (Auth::user()->can('roles.create')) --}}
        @can('create', 'App\Models\Role')
            <a class="btn btn-sm btn-outline-primary mr-2" href="{{ route('dashboard.roles.create') }}">Create</a>
        @endcan
        {{-- @endif --}}
        {{-- <a href="{{ route('dashboard.roles.index') }}" class="btn btn-sm btn-outline-primary mr-2">Back</a> --}}
        {{-- <a href="{{ route('dashboard.roles.trash') }}" class="btn btn-sm btn-outline-dark">Trash</a> --}}
    </div>

    <x-alert type="success" />
    <x-alert type="info" />

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Created At</th>
                <th colspan="2"></th>
            </tr>
        </thead>
        <tbody>
            @forelse($roles as $role)
                <tr>
                    <td>{{ $role->id }}</td>
                    <td><a href="{{ route('dashboard.roles.show', $role->id) }}">{{ $role->name }}</a></td>
                    <td>{{ $role->created_at }}</td>
                    <td>
                        {{-- <a href="{{ route('roles.edit', ['role' => $role->id]) }}"  or --}}
                        {{-- other way to is he can make edit for role --}}
                        @can('update', $role)
                            <a href="{{ route('dashboard.roles.edit', $role->id) }}"
                                class="btn btn-sm btn-outline-success">Edit</a>
                        @endcan
                    </td>
                    <td>
                        {{-- this user with policy --}}
                        @can('delete', $role)
                            {{-- @can('roles.delete') here use with Gate --}}
                            <form action="{{ route('dashboard.roles.destroy', $role->id) }}" method="post">
                                @csrf
                                {{-- Form Method Spoofing  --}}
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
                            </form>
                        @endcan
                    </td>
                </tr>
            @empty
                <td colspan="4" class="alert alert-danger" role="alert">
                    Roles Not Definded
                </td>
            @endforelse
        </tbody>
    </table>

    {{ $roles->links() }}

@endsection
