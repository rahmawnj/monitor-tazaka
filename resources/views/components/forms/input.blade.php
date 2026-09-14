@props([
    'label',
    'name',
    'type' => 'text',
    'value' => null,
    'placeholder' => '',
    'required' => false,
    'min' => null,
    'max' => null,
    'step' => null,
])

<div class="field">
    <label for="{{ $name }}">{{ $label }} @if($required)<span>*</span>@endif</label>
    <input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}" value="{{ old($name, $value) }}" placeholder="{{ $placeholder }}" @if($required) required @endif @if($min !== null) min="{{ $min }}" @endif @if($max !== null) max="{{ $max }}" @endif @if($step !== null) step="{{ $step }}" @endif {{ $attributes->merge(['class' => 'control']) }}>
    @error($name)<small class="error">{{ $message }}</small>@enderror
</div>
