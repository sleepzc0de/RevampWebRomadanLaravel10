@props([
    'name',
    'label' => null,
    'value' => null,
    'required' => false,
    'maxlength' => null,
    'placeholder' => null,
    'rich' => false,
    'rows' => 5,
    'help' => null,
])

@php
    $inputId = $attributes->get('id', $name);
    $val = old($name, $value);
    $class = trim('form-control ' . ($rich ? 'js-rich-editor ' : '') . ($errors->has($name) ? 'is-invalid' : ''));
@endphp

<div class="mb-4">
    @if($label)
        <label for="{{ $inputId }}" class="form-label mb-1.5 block">
            {{ $label }}
            @if($required)<span class="text-danger">*</span>@endif
        </label>
    @endif

    <textarea
        name="{{ $name }}"
        id="{{ $inputId }}"
        rows="{{ $rows }}"
        @if($required) required @endif
        @if($maxlength) maxlength="{{ $maxlength }}" @endif
        @if($placeholder) placeholder="{{ $placeholder }}" @endif
        {{ $attributes->except('id')->merge(['class' => $class]) }}
    >{{ $val }}</textarea>

    @error($name)
        <span class="invalid-feedback">{{ $message }}</span>
    @enderror

    @if($help)
        <span class="form-text">{{ $help }}</span>
    @endif
</div>
