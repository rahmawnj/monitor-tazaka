@props(['label', 'name', 'value' => null, 'rows' => 4, 'placeholder' => ''])

<div class="field">
    <label for="{{ $name }}">{{ $label }}</label>
    <textarea id="{{ $name }}" name="{{ $name }}" rows="{{ $rows }}" placeholder="{{ $placeholder }}" {{ $attributes->merge(['class' => 'control']) }}>{{ old($name, $value) }}</textarea>
    @error($name)<small class="error">{{ $message }}</small>@enderror
</div>
