@props([
    'name',
    'label' => null,
    'type' => 'text',
    'value' => null,
    'required' => false,
    'maxlength' => null,
    'placeholder' => null,
    'help' => null,
])

@php
    $inputId = $attributes->get('id', $name);
    $val = old($name, $value);
@endphp

<div class="mb-4">
    @if($label)
        <label for="{{ $inputId }}" class="form-label mb-1.5 block">
            {{ $label }}
            @if($required)<span class="text-danger">*</span>@endif
        </label>
    @endif

    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $inputId }}"
        value="{{ $val }}"
        @if($required) required @endif
        @if($maxlength) maxlength="{{ $maxlength }}" @endif
        @if($placeholder) placeholder="{{ $placeholder }}" @endif
        {{ $attributes->except('id')->merge(['class' => 'form-control' . ($errors->has($name) ? ' is-invalid' : '')]) }}
    >

    @error($name)
        <span class="invalid-feedback">{{ $message }}</span>
    @enderror

    @if($help)
        <span class="form-text">{{ $help }}</span>
    @endif
</div>
