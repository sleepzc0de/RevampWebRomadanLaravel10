@extends('layouts.webromadan_backend.master_layout')

@section('css')
<style>
    .struktur-children { border-color: #d8dee6 !important; }
    .struktur-card { border-radius: 12px; }
    .pejabat-chip { background: #f8f9fb; }
</style>
@endsection

@section('content')
<div class="card mb-3">
    <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
        <h5 class="mb-0">Struktur Organisasi</h5>
    </div>
    @include('layouts.webromadan_backend.session_notif')
    <div class="card-body">
        @if ($root)
            @include('backend.struktur-jabatan._node', ['node' => $root, 'byParent' => $byParent])
        @else
            <div class="text-center text-muted py-5">
                <p>Belum ada jabatan tertinggi.</p>
                <button type="button" class="btn btn-primary add-jabatan-btn" data-parent-id="" data-parent-nama="(Jabatan Tertinggi)">
                    <i class="ph-plus"></i> Tambah Jabatan Tertinggi
                </button>
            </div>
        @endif
    </div>
</div>

{{-- Modal Tambah Jabatan / Sub-Jabatan --}}
<div class="modal fade" id="modalAddJabatan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('struktur-jabatan.jabatan.store') }}">
                @csrf
                <div class="modal-header">
                    <h6 class="modal-title">Tambah Jabatan</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small mb-3">Induk: <strong id="addJabatanParentLabel">-</strong></p>
                    <input type="hidden" name="parent_id" id="addJabatanParentId">
                    <div class="mb-3">
                        <label class="form-label">Nama Jabatan</label>
                        <input type="text" name="nama_jabatan" class="form-control" required maxlength="255">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Urutan (opsional)</label>
                        <input type="number" name="urutan" class="form-control" value="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Edit Jabatan --}}
<div class="modal fade" id="modalEditJabatan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id="editJabatanForm" action="">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h6 class="modal-title">Edit Jabatan</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Jabatan</label>
                        <input type="text" name="nama_jabatan" id="editJabatanNama" class="form-control" required maxlength="255">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Urutan</label>
                        <input type="number" name="urutan" id="editJabatanUrutan" class="form-control" value="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Tambah Pejabat --}}
<div class="modal fade" id="modalAddPejabat" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('struktur-jabatan.pejabat.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h6 class="modal-title">Tambah Pejabat</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small mb-3">Jabatan: <strong id="addPejabatJabatanLabel">-</strong></p>
                    <input type="hidden" name="jabatan_id" id="addPejabatJabatanId">
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="nama" class="form-control" required maxlength="255">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Foto (opsional)</label>
                        <input type="file" name="foto" class="form-control" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Edit Pejabat --}}
<div class="modal fade" id="modalEditPejabat" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id="editPejabatForm" action="" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h6 class="modal-title">Edit Pejabat</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="nama" id="editPejabatNama" class="form-control" required maxlength="255">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ganti Foto (opsional)</label>
                        <input type="file" name="foto" class="form-control" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Konfirmasi Hapus (dipakai bersama untuk jabatan & pejabat) --}}
<div class="modal fade" id="modalDelete" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Konfirmasi Hapus</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" id="deleteForm" action="">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>Yakin ingin menghapus <strong id="deleteLabel"></strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script_bawah')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const addJabatanModal = new bootstrap.Modal(document.getElementById('modalAddJabatan'));
    const editJabatanModal = new bootstrap.Modal(document.getElementById('modalEditJabatan'));
    const addPejabatModal = new bootstrap.Modal(document.getElementById('modalAddPejabat'));
    const editPejabatModal = new bootstrap.Modal(document.getElementById('modalEditPejabat'));
    const deleteModal = new bootstrap.Modal(document.getElementById('modalDelete'));

    document.querySelectorAll('.add-jabatan-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.getElementById('addJabatanParentId').value = btn.dataset.parentId || '';
            document.getElementById('addJabatanParentLabel').textContent = btn.dataset.parentNama || '(Jabatan Tertinggi)';
            addJabatanModal.show();
        });
    });

    document.querySelectorAll('.edit-jabatan-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.getElementById('editJabatanForm').action =
                "{{ route('struktur-jabatan.jabatan.update', 'REPLACE_ID') }}".replace('REPLACE_ID', btn.dataset.id);
            document.getElementById('editJabatanNama').value = btn.dataset.nama;
            document.getElementById('editJabatanUrutan').value = btn.dataset.urutan || 0;
            editJabatanModal.show();
        });
    });

    document.querySelectorAll('.add-pejabat-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.getElementById('addPejabatJabatanId').value = btn.dataset.jabatanId;
            document.getElementById('addPejabatJabatanLabel').textContent = btn.dataset.jabatanNama;
            addPejabatModal.show();
        });
    });

    document.querySelectorAll('.edit-pejabat-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.getElementById('editPejabatForm').action =
                "{{ route('struktur-jabatan.pejabat.update', 'REPLACE_ID') }}".replace('REPLACE_ID', btn.dataset.id);
            document.getElementById('editPejabatNama').value = btn.dataset.nama;
            editPejabatModal.show();
        });
    });

    document.querySelectorAll('.delete-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.getElementById('deleteForm').action = btn.dataset.action;
            document.getElementById('deleteLabel').textContent = btn.dataset.label;
            deleteModal.show();
        });
    });
});
</script>
@endsection
