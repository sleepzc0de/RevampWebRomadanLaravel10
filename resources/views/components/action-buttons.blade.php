<div class="d-inline-flex">
    <div class="dropdown">
        <a href="#" class="text-body" data-bs-toggle="dropdown">
            <i class="ph-list"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-end">
            <a href="{{ $edit }}" class="dropdown-item">
                <i class="ph-note-pencil me-2"></i>
                Edit
            </a>
            <form action="{{ $hapus }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="dropdown-item">
                    <i class="ph-trash me-2"></i>
                    Hapus
                </button>
            </form>
        </div>
    </div>
</div>
