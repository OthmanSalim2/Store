<div class="form-group m-2">

    <x-form.input label="Category Name" class="form-control-lg" type="text" name="name" :value="$role->name" />
    {{-- other way <x-form.input $type="text" name="name" value="{{$role->name}}" /> --}}
</div>

<fieldset>
    <legend>{{ __('Abilities') }}</legend>

    {{-- other way App::make('abilites') --}}
    @foreach (app('abilities') as $ability_code => $ability_name)
        <div class="row mb-2">
            <div class="col-md-6">
                {{-- is_callable() it'ss use to check Is it the returned value is function if yes call as funvtion --}}
                {{ is_callable($ability_name) ? $ability_name() : $ability_name }}
            </div>
            <div class="col-md-2">
                <input type="radio" name="abilities[{{ $ability_code }}]" value="allow"
                    @checked(($role_abilities[$ability_code] ?? '') == 'allow')>Allow
            </div>
            <div class="col-md-2">
                <input type="radio" name="abilities[{{ $ability_code }}]" value="deny"
                    @checked(($role_abilities[$ability_code] ?? '') == 'deny')>Deny
            </div>
            <div class="col-md-2">
                <input type="radio" name="abilities[{{ $ability_code }}]" value="inherit"
                    @checked(($role_abilities[$ability_code] ?? '') == 'inherit')>Inherit
            </div>
        </div>
    @endforeach
</fieldset>

<div class="form-group m-2">
    <button type="submit" class="btn btn-primary"> {{ $button_label ?? 'Save' }} </button>
</div>
