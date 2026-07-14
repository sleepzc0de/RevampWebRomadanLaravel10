@props([
    'preview' => null,
    'edit' => null,
    'destroy' => null,
    'restore' => null,
    'forceDelete' => null,
])

<div class="d-inline-flex">
    <div class="dropdown">
        <a href="#" class="text-body" data-bs-toggle="dropdown">
            <i class="ph-list"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-end">
            @if ($preview)
                <a href="{{ $preview }}" class="dropdown-item">
                    <i class="ph-detective me-2"></i> Preview
                </a>
            @endif

            @if ($edit)
                <a href="{{ $edit }}" class="dropdown-item">
                    <i class="ph-note-pencil me-2"></i> Edit
                </a>
            @endif

            @if ($restore)
                <form action="{{ $restore }}" method="POST">
                    @csrf
                    <button type="submit" class="dropdown-item">
                        <i class="ph-arrow-counter-clockwise me-2"></i> Restore
                    </button>
                </form>
            @endif

            @if ($destroy)
                <form action="{{ $destroy }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="dropdown-item">
                        <i class="ph-trash me-2"></i> Hapus
                    </button>
                </form>
            @endif

            @if ($forceDelete)
                <form action="{{ $forceDelete }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus PERMANEN data ini? Tindakan ini tidak bisa dibatalkan.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="dropdown-item">
                        <i class="ph-trash me-2"></i> Paksa Hapus
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
