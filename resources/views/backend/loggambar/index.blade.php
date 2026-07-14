@extends('layouts.webromadan_backend.master_layout')

@section('content')
<x-data-table
    title="Gambar Login"
    :ajax="route('loggambar.index')"
    :create-route="route('loggambar.create')"
    create-label="Tambah Data"
    search-placeholder="Cari gambar..."
    :columns="[
        ['label' => '#', 'data' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false, 'class' => 'w-12'],
        ['label' => 'Nama Gambar', 'data' => 'nama_gambar'],
        ['label' => 'Gambar', 'data' => 'image_loggambar', 'name' => 'image_loggambar', 'orderable' => false, 'searchable' => false, 'raw' => true],
        ['label' => 'Aksi', 'data' => 'opsi', 'orderable' => false, 'searchable' => false, 'raw' => true, 'class' => 'w-24'],
    ]"
>
    <x-slot:notice>
        @include('layouts.webromadan_backend.session_notif')
    </x-slot:notice>
</x-data-table>
@endsection
