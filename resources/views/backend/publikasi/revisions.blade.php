@extends('layouts.webromadan_backend.master_layout')

@section('css')
@endsection

@section('script_bawah')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.revision-view-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.getElementById('revisionViewJudul').textContent = btn.dataset.judul;
            document.getElementById('revisionViewSubJudul').textContent = btn.dataset.subJudul || '-';
            document.getElementById('revisionViewStatus').textContent = btn.dataset.status || '-';
            document.getElementById('revisionViewIsi').innerHTML = btn.dataset.isi;
            new bootstrap.Modal(document.getElementById('revisionViewModal')).show();
        });
    });

    document.querySelectorAll('.revision-restore-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.getElementById('revisionRestoreForm').action = btn.dataset.action;
            document.getElementById('revisionRestoreLabel').textContent = btn.dataset.judul;
            new bootstrap.Modal(document.getElementById('revisionRestoreModal')).show();
        });
    });
});
</script>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
        <h5 class="mb-0">Riwayat Revisi — {{ $publikasi->judul }}</h5>
        <a href="{{ route('publikasi.edit', encrypt($publikasi->id)) }}" class="btn btn-sm btn-outline-primary">
            <i class="ph-caret-double-left"></i> Kembali ke Edit
        </a>
    </div>
    @include('layouts.webromadan_backend.session_notif')

    <table class="table table-hover table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Waktu</th>
                <th>Diubah Oleh</th>
                <th>Judul Saat Itu</th>
                <th>Status Saat Itu</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($revisions as $revision)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $revision->created_at?->locale('id')->isoFormat('D MMM Y HH:mm:ss') }}</td>
                    <td>{{ $revision->revised_by }}</td>
                    <td>{{ $revision->judul }}</td>
                    <td>{{ $revision->status }}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-light revision-view-btn"
                            data-judul="{{ $revision->judul }}"
                            data-sub-judul="{{ $revision->sub_judul }}"
                            data-status="{{ $revision->status }}"
                            data-isi="{{ clean($revision->isi) }}">
                            <i class="ph-eye"></i> Lihat
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-warning revision-restore-btn"
                            data-judul="{{ $revision->judul }}"
                            data-action="{{ route('publikasi.revisions.restore', [encrypt($publikasi->id), encrypt($revision->id)]) }}">
                            <i class="ph-arrow-counter-clockwise"></i> Pulihkan
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">Belum ada riwayat revisi untuk publikasi ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Modal lihat isi revisi -->
<div class="modal fade" id="revisionViewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Detail Revisi</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-1">Judul</p>
                <p id="revisionViewJudul" class="fw-bold"></p>
                <p class="text-muted mb-1">Sub Judul</p>
                <p id="revisionViewSubJudul"></p>
                <p class="text-muted mb-1">Status</p>
                <p id="revisionViewStatus"></p>
                <p class="text-muted mb-1">Isi</p>
                <div id="revisionViewIsi" class="border rounded p-3"></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal konfirmasi pulihkan -->
<div class="modal fade" id="revisionRestoreModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Pulihkan Revisi</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="revisionRestoreForm" method="POST" action="">
                @csrf
                <div class="modal-body">
                    <p>Pulihkan publikasi ke kondisi: <strong id="revisionRestoreLabel"></strong>?</p>
                    <p class="text-muted small mb-0">Kondisi saat ini akan disimpan dulu sebagai revisi baru, jadi tindakan ini tetap bisa dibatalkan.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Ya, Pulihkan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
