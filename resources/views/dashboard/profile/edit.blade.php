@extends('layouts.dashboard')

@section('title', 'Edit Profile')

@section('breadceumb')
    @parent
    <li class="breadcrumb-item active">Starter Page</li>
@endsection

@section('content')

    <x-alert type="success" />

    <form action="{{ route('dashboard.profile.update') }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        <div class="form-row">
            <div class="col-md-6">
                <x-form.input name="first_name" label="First Name" :vlaue="$user->profile->first_name" />
            </div>

            <div class="col-md-6">
                <x-form.input name="last_name" label="Last Name" :vlaue="$user->profile->last_name" />
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-6">
                <x-form.input name="birthday" type="date" label="Birthday" :vlaue="$user->profile->birthday" />
            </div>

            <div class="col-md-6">
                <label for="">Gender</label>
                <x-form.radio name="gender" label="Gender" :options="['male' => 'Male', 'female' => 'Female']" :checked="$user->profile->gender" />
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-4">
                <x-form.input name="street_address" label="Street Address" :vlaue="$user->profile->street_address" />
            </div>
            <div class="col-md-4">
                <x-form.input name="city" label="City" :vlaue="$user->profile->city" />
            </div>
            <div class="col-md-4">
                <x-form.input name="state" label="State" :vlaue="$user->profile->state" />
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-4">
                <x-form.input name="postal_code" label="Postal Code" :vlaue="$user->profile->postal_code" />
            </div>
            <div class="col-md-4">
                <x-form.second-select name="country" :options="$countries" label="Country" :selected="$user->profile->country" />
            </div>
            <div class="col-md-4">
                <x-form.second-select name="locale" :options="$locales" label="Locale" :selected="$user->profile->locale" />
            </div>
        </div>
        <button style="margin-top:20px;" class="btn btn-outline-primary">Save</button>
    </form>

@endsection
