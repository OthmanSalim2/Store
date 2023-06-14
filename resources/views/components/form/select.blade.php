@props(['name', 'parents', 'value'])



<select name="{{ $name }}" class="form-control form-select">
    <option value="">Primary Category</option>
    @foreach ($parents as $parent)
        <option value="{{ $parent->id }}" @selected(old($name, $value) == $parent->id)>{{ $parent->name }}</option>
    @endforeach
</select>
