@props([
    'show',
    'close' => 'modal = null',
    'title',
])

<div x-show="{{ $show }}" x-cloak x-transition.opacity class="modal-backdrop" @click="{{ $close }}"></div>
<div x-show="{{ $show }}" x-cloak class="modal">
    <div class="modal-dialog">
        <div class="modal-content" @click.outside="{{ $close }}" x-transition.scale.origin.center>
            <div class="modal-header">
                <h6 class="modal-title">{{ $title }}</h6>
                <button type="button" class="btn-close" @click="{{ $close }}"></button>
            </div>
            {{ $slot }}
        </div>
    </div>
</div>
