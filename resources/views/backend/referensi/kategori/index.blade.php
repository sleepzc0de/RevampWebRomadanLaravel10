@extends('layouts.webromadan_backend.master_layout')

@section('content')
<x-data-table
    title="Kategori Berita"
    :ajax="route('kategori.index')"
    :create-route="route('kategori.create')"
    create-label="Tambah Kategori"
    search-placeholder="Cari kategori..."
    :columns="[
        ['label' => '#', 'data' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false, 'class' => 'w-12'],
        ['label' => 'Nama Kategori', 'data' => 'nama_kategori'],
        ['label' => 'Aksi', 'data' => 'opsi', 'orderable' => false, 'searchable' => false, 'raw' => true, 'class' => 'w-24'],
    ]"
>
    <x-slot:notice>
        @include('layouts.webromadan_backend.session_notif')
    </x-slot:notice>
</x-data-table>
@endsection
