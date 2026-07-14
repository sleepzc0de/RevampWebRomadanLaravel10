@extends('layouts.webromadan_backend.master_layout')

@section('content')
<x-data-table
    title="Peraturan"
    :ajax="route('peraturan.index')"
    :create-route="route('peraturan.create')"
    create-label="Tambah Peraturan"
    search-placeholder="Cari peraturan..."
    :columns="[
        ['label' => '#', 'data' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false, 'class' => 'w-12'],
        ['label' => 'Nomor Peraturan', 'data' => 'nomor_peraturan'],
        ['label' => 'Judul Peraturan', 'data' => 'judul_peraturan'],
        ['label' => 'File Peraturan', 'data' => 'file_peraturan', 'name' => 'file_peraturan', 'orderable' => false, 'searchable' => false, 'raw' => true],
        ['label' => 'Kategori', 'data' => 'kategori.nama_kategori', 'name' => 'kategori.nama_kategori', 'orderable' => false, 'searchable' => false],
        ['label' => 'Jenis Peraturan', 'data' => 'data_jenis_peraturan.nama_jenis_peraturan', 'name' => 'data_jenis_peraturan.nama_jenis_peraturan', 'orderable' => false, 'searchable' => false],
        ['label' => 'Tanggal Penetapan', 'data' => 'tanggal_penetapan'],
        ['label' => 'Tanggal Berlaku', 'data' => 'tanggal_berlaku'],
        ['label' => 'Status Peraturan', 'data' => 'data_status_peraturan.nama_peraturan_status', 'name' => 'data_status_peraturan.nama_peraturan_status', 'orderable' => false, 'searchable' => false],
        ['label' => 'Slug', 'data' => 'slug', 'class' => 'max-w-[10rem] truncate'],
        ['label' => 'Aksi', 'data' => 'opsi', 'orderable' => false, 'searchable' => false, 'raw' => true, 'class' => 'w-24'],
    ]"
>
    <x-slot:notice>
        @include('layouts.webromadan_backend.session_notif')
    </x-slot:notice>
</x-data-table>
@endsection
