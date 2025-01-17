// First, create a new file: resources/views/components/action-buttons.blade.php
<div class="d-inline-flex">
    <div class="dropdown">
        <a href="#" class="text-body" data-bs-toggle="dropdown">
            <i class="ph-list"></i>
        </a>

        <div class="dropdown-menu dropdown-menu-end">
            <a href="{{ $preview }}" class="dropdown-item">
                <i class="ph-detective me-2"></i>
                Preview
            </a>
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
