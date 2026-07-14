@extends('layouts.webromadan_backend.master_layout')

@section('content')
<x-data-table
    title="Pedoman"
    :ajax="route('pedoman.index')"
    :create-route="route('pedoman.create')"
    create-label="Tambah Pedoman"
    search-placeholder="Cari pedoman..."
    :columns="[
        ['label' => '#', 'data' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false, 'class' => 'w-12'],
        ['label' => 'Judul Pedoman', 'data' => 'judul_pedoman'],
        ['label' => 'File Pedoman', 'data' => 'file_pedoman', 'name' => 'file_pedoman', 'orderable' => false, 'searchable' => false, 'raw' => true],
        ['label' => 'Kategori', 'data' => 'dataKategori.nama_kategori', 'name' => 'dataKategori.nama_kategori', 'orderable' => false, 'searchable' => false],
        ['label' => 'Tanggal Terbit', 'data' => 'tanggal_terbit'],
        ['label' => 'Aksi', 'data' => 'opsi', 'orderable' => false, 'searchable' => false, 'raw' => true, 'class' => 'w-24'],
    ]"
>
    <x-slot:notice>
        @include('layouts.webromadan_backend.session_notif')
    </x-slot:notice>
</x-data-table>
@endsection
