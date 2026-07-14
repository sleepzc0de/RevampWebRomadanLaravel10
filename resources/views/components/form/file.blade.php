@props([
    'name',
    'label' => null,
    'required' => false,
    'accept' => 'image/*',
    'currentUrl' => null,
    'help' => null,
])

@php $inputId = $attributes->get('id', $name); @endphp

<div class="mb-4" x-data="filePreview({{ Illuminate\Support\Js::from($currentUrl) }})">
    @if($label)
        <label for="{{ $inputId }}" class="form-label mb-1.5 block">
            {{ $label }}
            @if($required)<span class="text-danger">*</span>@endif
        </label>
    @endif

    <input
        type="file"
        name="{{ $name }}"
        id="{{ $inputId }}"
        accept="{{ $accept }}"
        @if($required) required @endif
        @change="onChange($event)"
        {{ $attributes->except('id')->merge(['class' => 'form-control' . ($errors->has($name) ? ' is-invalid' : '')]) }}
    >

    <template x-if="preview">
        <img :src="preview" class="img-thumbnail mt-2 max-h-44" alt="Preview">
    </template>

    @error($name)
        <span class="invalid-feedback">{{ $message }}</span>
    @enderror

    @if($help)
        <span class="form-text">{{ $help }}</span>
    @endif
</div>
