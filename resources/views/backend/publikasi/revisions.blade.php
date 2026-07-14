@extends('layouts.webromadan_backend.master_layout')

@section('content')
<div x-data="{ open: false, judul: '', subJudul: '', status: '', isi: '' }" @keydown.window.escape="open = false">
    <div class="card">
        <div class="card-header flex flex-wrap items-center justify-between gap-2">
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
                            <button type="button" class="btn btn-sm btn-light"
                                @click="open = true; judul = @js($revision->judul); subJudul = @js($revision->sub_judul ?? '-'); status = @js($revision->status ?? '-'); isi = @js(clean($revision->isi))">
                                <i class="ph-eye"></i> Lihat
                            </button>
                            <form method="POST" action="{{ route('publikasi.revisions.restore', [encrypt($publikasi->id), encrypt($revision->id)]) }}"
                                  class="d-inline-block" onsubmit="return confirm('Pulihkan publikasi ke kondisi: {{ addslashes($revision->judul) }}?')">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-warning">
                                    <i class="ph-arrow-counter-clockwise"></i> Pulihkan
                                </button>
                            </form>
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

    <div x-show="open" x-cloak x-transition.opacity class="modal-backdrop" @click="open = false"></div>
    <div x-show="open" x-cloak class="modal" style="display: block;">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content" @click.outside="open = false">
                <div class="modal-header">
                    <h6 class="modal-title">Detail Revisi</h6>
                    <button type="button" class="btn-close" @click="open = false"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted mb-1">Judul</p>
                    <p class="fw-bold" x-text="judul"></p>
                    <p class="text-muted mb-1">Sub Judul</p>
                    <p x-text="subJudul"></p>
                    <p class="text-muted mb-1">Status</p>
                    <p x-text="status"></p>
                    <p class="text-muted mb-1">Isi</p>
                    <div class="border rounded p-3" x-html="isi"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
