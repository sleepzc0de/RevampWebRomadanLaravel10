@props([
    'name',
    'label' => null,
    'options' => [],
    'value' => null,
    'required' => false,
    'placeholder' => null,
    'help' => null,
])

@php
    $inputId = $attributes->get('id', $name);
    $selected = old($name, $value);
@endphp

<div class="mb-4">
    @if($label)
        <label for="{{ $inputId }}" class="form-label mb-1.5 block">
            {{ $label }}
            @if($required)<span class="text-danger">*</span>@endif
        </label>
    @endif

    <select
        name="{{ $name }}"
        id="{{ $inputId }}"
        @if($required) required @endif
        {{ $attributes->except('id')->merge(['class' => 'select' . ($errors->has($name) ? ' is-invalid' : '')]) }}
    >
        @if($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif

        @foreach($options as $optValue => $optLabel)
            <option value="{{ $optValue }}" @selected((string) $selected === (string) $optValue)>{{ $optLabel }}</option>
        @endforeach
    </select>

    @error($name)
        <span class="invalid-feedback">{{ $message }}</span>
    @enderror

    @if($help)
        <span class="form-text">{{ $help }}</span>
    @endif
</div>
