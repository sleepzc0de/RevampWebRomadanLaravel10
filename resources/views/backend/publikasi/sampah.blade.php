@extends('layouts.webromadan_backend.master_layout')

@section('content')
<x-data-table
    title="Publikasi Terhapus"
    :ajax="route('publikasi.sampah')"
    search-placeholder="Cari publikasi..."
    :columns="[
        ['label' => '#', 'data' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false, 'class' => 'w-12'],
        ['label' => 'Judul', 'data' => 'judul'],
        ['label' => 'Gambar', 'data' => 'image_publikasi', 'name' => 'image_publikasi', 'orderable' => false, 'searchable' => false, 'raw' => true],
        ['label' => 'Tipe', 'data' => 'tipe.nama_tipe', 'name' => 'tipe.nama_tipe', 'orderable' => false, 'searchable' => false],
        ['label' => 'Kategori', 'data' => 'kategori.nama_kategori', 'name' => 'kategori.nama_kategori', 'orderable' => false, 'searchable' => false],
        ['label' => 'Status', 'data' => 'status.nama_status', 'name' => 'status.nama_status', 'orderable' => false, 'searchable' => false],
        ['label' => 'Penulis', 'data' => 'penulis'],
        ['label' => 'Aksi', 'data' => 'opsi', 'orderable' => false, 'searchable' => false, 'raw' => true, 'class' => 'w-24'],
    ]"
>
    <x-slot:notice>
        @include('layouts.webromadan_backend.session_notif')
    </x-slot:notice>

    <x-slot:headerActions>
        <a href="{{ route('publikasi.index') }}" class="btn btn-flat-success">
            <i class="ph-list"></i> Daftar Publikasi Aktif
        </a>
        <form action="{{ route('publikasi.restore-all') }}" method="POST" onsubmit="return confirm('Pulihkan semua publikasi yang terhapus?')">
            @csrf
            <button type="submit" class="btn btn-flat-warning">
                <i class="ph-arrow-counter-clockwise"></i> Restore Semua Publikasi
            </button>
        </form>
    </x-slot:headerActions>
</x-data-table>
@endsection
