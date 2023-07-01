<select name="{{ $name }}"
    class=" form-control form-select @error($name)
       {{ $message }}
   @enderror">

    <option value="">Chooose Country</option>
    @foreach ($options as $value => $text)
        <option value="{{ $value }}" @selected($value == $selected)> {{ $text }} </option>
    @endforeach

</select>
