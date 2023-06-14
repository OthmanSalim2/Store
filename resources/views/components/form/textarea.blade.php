@props(['name', 'value' => '', 'label' => false])

@if ($label)
    <label for="">{{ $label }}</label>
@endif

<textarea name="{{ $name }}" {{ $attributes->class(['form-control', 'is-invalid' => $errors->has($name)]) }}>{{ old('name', $value) }}</textarea>
@error($name)
    <div class="text-danger">
        <!-- This already defined in laravel just of error inside it and diplay the first error -->
        {{ $message }}
    </div>
@enderror
