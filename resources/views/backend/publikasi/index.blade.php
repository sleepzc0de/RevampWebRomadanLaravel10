@extends('layouts.webromadan_backend.master_layout')

@section('content')
<x-data-table
    title="Publikasi"
    :ajax="route('publikasi.index')"
    :create-route="route('publikasi.create')"
    create-label="Tambah Data"
    search-placeholder="Cari publikasi..."
    :order="[8, 'desc']"
    :columns="[
        ['label' => '#', 'data' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false, 'class' => 'w-12'],
        ['label' => 'Judul', 'data' => 'file_publikasi', 'name' => 'file_publikasi', 'orderable' => false, 'searchable' => false, 'raw' => true],
        ['label' => 'Gambar', 'data' => 'image_publikasi', 'name' => 'image_publikasi', 'orderable' => false, 'searchable' => false, 'raw' => true],
        ['label' => 'Tipe', 'data' => 'tipe.nama_tipe', 'name' => 'tipe.nama_tipe', 'orderable' => false, 'searchable' => false],
        ['label' => 'Kategori', 'data' => 'kategori.nama_kategori', 'name' => 'kategori.nama_kategori', 'orderable' => false, 'searchable' => false],
        ['label' => 'Status', 'data' => 'status.nama_status', 'name' => 'status.nama_status', 'orderable' => false, 'searchable' => false],
        ['label' => 'Penulis', 'data' => 'penulis'],
        ['label' => 'Pengedit', 'data' => 'pengedit'],
        ['label' => 'Tanggal Publikasi', 'data' => 'created_at'],
        ['label' => 'Aksi', 'data' => 'opsi', 'orderable' => false, 'searchable' => false, 'raw' => true, 'class' => 'w-24'],
    ]"
>
    <x-slot:notice>
        @include('layouts.webromadan_backend.session_notif')
    </x-slot:notice>

    <x-slot:headerActions>
        <a href="{{ route('publikasi.sampah') }}" class="btn btn-flat-pink">
            <i class="ph-trash"></i> Daftar Publikasi Terhapus
        </a>
        <a href="{{ route('publikasi.export') }}" class="btn btn-flat-success">
            <i class="ph-file-xls"></i> Ekspor Excel
        </a>
    </x-slot:headerActions>
</x-data-table>
@endsection
