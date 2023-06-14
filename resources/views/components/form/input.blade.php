@props([
    'type' => 'text',
    'name',
    'value' => '',
    'label' => false,
])

@if ($label)
    <label for="">{{ $label }}</label>
@endif

<input type="{{ $type }}" name="{{ $name }}"
    {{ $attributes->class(['form-control', 'is-invalid' => $errors->has($name)]) }} value="{{ old('name', $value) }}">
@error($name)
    <div class="text-danger">
        <!-- This already defined in laravel just of error inside it and diplay the first error -->
        {{ $message }}
    </div>
@enderror
