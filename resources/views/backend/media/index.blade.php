@extends('layouts.webromadan_backend.master_layout')

@section('content')
<div class="row g-3 mb-1">
    @php
        $cards = [
            ['label' => 'Total File', 'value' => $kpi['total'], 'icon' => 'ph-folders', 'c' => 'var(--color-brand-500)'],
            ['label' => 'Gambar', 'value' => $kpi['images'], 'icon' => 'ph-image', 'c' => '#059669'],
            ['label' => 'Dokumen', 'value' => $kpi['documents'], 'icon' => 'ph-file-text', 'c' => '#d69e00'],
        ];
    @endphp
    @foreach($cards as $card)
    <div class="col-xl-3 col-sm-6">
        <div class="cms-stat h-full" style="border-inline-start-color: {{ $card['c'] }};">
            <i class="{{ $card['icon'] }} cms-stat-icon"></i>
            <span class="cms-stat-label">{{ $card['label'] }}</span>
            <span class="cms-stat-value">{{ number_format($card['value'], 0, ',', '.') }}</span>
        </div>
    </div>
    @endforeach
    <div class="col-xl-3 col-sm-6">
        <div class="cms-stat h-full" style="border-inline-start-color: #64748b;">
            <i class="ph-hard-drives cms-stat-icon"></i>
            <span class="cms-stat-label">Total Ukuran</span>
            <span class="cms-stat-value">{{ $kpi['total_size'] }}</span>
        </div>
    </div>
</div>

<div x-data="{ showDelete: false, deleteId: '', deleteName: '' }"
     @click="const btn = $event.target.closest('.media-delete-btn'); if (btn) { deleteId = btn.dataset.id; deleteName = btn.dataset.name; showDelete = true; }"
     @keydown.window.escape="showDelete = false">
    <x-data-table
        title="Media Library"
        :ajax="route('media.index')"
        search-placeholder="Cari nama file..."
        :order="[5, 'desc']"
        :columns="[
            ['label' => '#', 'data' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false, 'class' => 'w-12'],
            ['label' => 'Preview', 'data' => 'preview', 'orderable' => false, 'searchable' => false, 'raw' => true, 'class' => 'w-20'],
            ['label' => 'Nama File', 'data' => 'filename'],
            ['label' => 'Ukuran', 'data' => 'ukuran', 'name' => 'size'],
            ['label' => 'Status', 'data' => 'digunakan', 'orderable' => false, 'searchable' => false, 'raw' => true],
            ['label' => 'Diunggah', 'data' => 'waktu', 'name' => 'created_at'],
            ['label' => 'Aksi', 'data' => 'opsi', 'orderable' => false, 'searchable' => false, 'raw' => true, 'class' => 'w-20'],
        ]"
    >
        <x-slot:notice>
            @include('layouts.webromadan_backend.session_notif')
        </x-slot:notice>

        <x-slot:headerActions>
            <select class="form-select form-select-sm" style="width:auto;" x-model="extra.type" @change="page = 0; load()">
                <option value="">Semua Tipe</option>
                <option value="image">Gambar</option>
            </select>
            <form action="{{ route('media.sync') }}" method="post">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-primary">
                    <i class="ph-arrows-clockwise"></i> Sinkronkan Sekarang
                </button>
            </form>
        </x-slot:headerActions>
    </x-data-table>

    {{-- Modal konfirmasi hapus --}}
    <div x-show="showDelete" x-cloak x-transition.opacity class="modal-backdrop" @click="showDelete = false"></div>
    <div x-show="showDelete" x-cloak class="modal">
        <div class="modal-dialog">
            <div class="modal-content" @click.outside="showDelete = false">
                <div class="modal-header">
                    <h6 class="modal-title">Hapus File</h6>
                    <button type="button" class="btn-close" @click="showDelete = false"></button>
                </div>
                <form method="POST" :action="'{{ route('media.destroy', '__ID__') }}'.replace('__ID__', deleteId)">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <p>Yakin ingin menghapus <strong x-text="deleteName"></strong>? Tindakan ini tidak bisa dibatalkan.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" @click="showDelete = false">Batal</button>
                        <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
