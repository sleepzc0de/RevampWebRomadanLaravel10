@props([
    'backRoute' => null,
    'backLabel' => 'Kembali',
    'submitLabel' => 'Simpan',
    'showReset' => true,
])

<div class="card-footer flex flex-wrap items-center justify-end gap-2">
    @if($backRoute)
        <a href="{{ $backRoute }}" class="btn btn-light">
            <i class="ph-caret-double-left"></i> {{ $backLabel }}
        </a>
    @endif

    @if($showReset)
        <button type="reset" class="btn btn-light">Reset</button>
    @endif

    <button type="submit" class="btn btn-primary">
        {{ $submitLabel }} <i class="ph-paper-plane-tilt"></i>
    </button>
</div>
