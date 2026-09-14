@props(['label', 'name', 'value' => null, 'required' => false])

<div class="field">
    <label for="{{ $name }}">{{ $label }} @if($required)<span>*</span>@endif</label>
    <select id="{{ $name }}" name="{{ $name }}" @if($required) required @endif {{ $attributes->merge(['class' => 'control']) }}>
        {{ $slot }}
    </select>
    @error($name)<small class="error">{{ $message }}</small>@enderror
</div>
