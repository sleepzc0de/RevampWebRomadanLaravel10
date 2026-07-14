@extends('layouts.webromadan_backend.master_layout')

@section('content')
<div x-data="strukturJabatanPage()" @click="handleClick($event)">
    <div class="card mb-3">
        <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h5 class="mb-0">Struktur Organisasi</h5>
                <p class="m-0 mt-1 text-xs font-normal text-slate-500 dark:text-slate-400">Kelola hirarki jabatan dan pejabat yang tampil pada bagan publik.</p>
            </div>
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
    <x-crud-modal show="modal === 'addJabatan'" title="Tambah Jabatan">
        <form method="POST" action="{{ route('struktur-jabatan.jabatan.store') }}">
            @csrf
            <div class="modal-body">
                <p class="text-muted small mb-3">Induk: <strong x-text="addJabatan.parentNama"></strong></p>
                <input type="hidden" name="parent_id" :value="addJabatan.parentId">
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
                <button type="button" class="btn btn-light" @click="modal = null">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </x-crud-modal>

    {{-- Modal Edit Jabatan --}}
    <x-crud-modal show="modal === 'editJabatan'" title="Edit Jabatan">
        <form method="POST" :action="editJabatan.action">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nama Jabatan</label>
                    <input type="text" name="nama_jabatan" class="form-control" required maxlength="255" x-model="editJabatan.nama">
                </div>
                <div class="mb-3">
                    <label class="form-label">Urutan</label>
                    <input type="number" name="urutan" class="form-control" x-model="editJabatan.urutan">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" @click="modal = null">Batal</button>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </x-crud-modal>

    {{-- Modal Tambah Pejabat --}}
    <x-crud-modal show="modal === 'addPejabat'" title="Tambah Pejabat">
        <form method="POST" action="{{ route('struktur-jabatan.pejabat.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <p class="text-muted small mb-3">Jabatan: <strong x-text="addPejabat.jabatanNama"></strong></p>
                <input type="hidden" name="jabatan_id" :value="addPejabat.jabatanId">
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
                <button type="button" class="btn btn-light" @click="modal = null">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </x-crud-modal>

    {{-- Modal Edit Pejabat --}}
    <x-crud-modal show="modal === 'editPejabat'" title="Edit Pejabat">
        <form method="POST" :action="editPejabat.action" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="nama" class="form-control" required maxlength="255" x-model="editPejabat.nama">
                </div>
                <div class="mb-3">
                    <label class="form-label">Ganti Foto (opsional)</label>
                    <input type="file" name="foto" class="form-control" accept="image/*">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" @click="modal = null">Batal</button>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </x-crud-modal>

    {{-- Modal Konfirmasi Hapus (dipakai bersama untuk jabatan & pejabat) --}}
    <x-crud-modal show="modal === 'delete'" title="Konfirmasi Hapus">
        <form method="POST" :action="del.action">
            @csrf
            @method('DELETE')
            <div class="modal-body">
                <p>Yakin ingin menghapus <strong x-text="del.label"></strong>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" @click="modal = null">Batal</button>
                <button type="submit" class="btn btn-danger">Ya, Hapus</button>
            </div>
        </form>
    </x-crud-modal>
</div>
@endsection

@push('scripts')
<script>
    function strukturJabatanPage() {
        return {
            modal: null,
            addJabatan: { parentId: '', parentNama: '(Jabatan Tertinggi)' },
            editJabatan: { action: '', nama: '', urutan: 0 },
            addPejabat: { jabatanId: '', jabatanNama: '' },
            editPejabat: { action: '', nama: '' },
            del: { action: '', label: '' },

            handleClick(event) {
                const addJabatanBtn = event.target.closest('.add-jabatan-btn');
                if (addJabatanBtn) {
                    this.addJabatan = {
                        parentId: addJabatanBtn.dataset.parentId || '',
                        parentNama: addJabatanBtn.dataset.parentNama || '(Jabatan Tertinggi)',
                    };
                    this.modal = 'addJabatan';
                    return;
                }

                const editJabatanBtn = event.target.closest('.edit-jabatan-btn');
                if (editJabatanBtn) {
                    this.editJabatan = {
                        action: "{{ route('struktur-jabatan.jabatan.update', 'REPLACE_ID') }}".replace('REPLACE_ID', editJabatanBtn.dataset.id),
                        nama: editJabatanBtn.dataset.nama,
                        urutan: editJabatanBtn.dataset.urutan || 0,
                    };
                    this.modal = 'editJabatan';
                    return;
                }

                const addPejabatBtn = event.target.closest('.add-pejabat-btn');
                if (addPejabatBtn) {
                    this.addPejabat = {
                        jabatanId: addPejabatBtn.dataset.jabatanId,
                        jabatanNama: addPejabatBtn.dataset.jabatanNama,
                    };
                    this.modal = 'addPejabat';
                    return;
                }

                const editPejabatBtn = event.target.closest('.edit-pejabat-btn');
                if (editPejabatBtn) {
                    this.editPejabat = {
                        action: "{{ route('struktur-jabatan.pejabat.update', 'REPLACE_ID') }}".replace('REPLACE_ID', editPejabatBtn.dataset.id),
                        nama: editPejabatBtn.dataset.nama,
                    };
                    this.modal = 'editPejabat';
                    return;
                }

                const deleteBtn = event.target.closest('.delete-btn');
                if (deleteBtn) {
                    this.del = { action: deleteBtn.dataset.action, label: deleteBtn.dataset.label };
                    this.modal = 'delete';
                }
            },
        };
    }
</script>
@endpush
