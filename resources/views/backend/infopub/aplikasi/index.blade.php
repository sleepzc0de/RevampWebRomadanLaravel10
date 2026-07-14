@extends('layouts.webromadan_backend.master_layout')

@section('content')
<x-data-table
    title="Portal Aplikasi"
    :ajax="route('aplikasi.index')"
    :create-route="route('aplikasi.create')"
    create-label="Tambah Aplikasi"
    search-placeholder="Cari aplikasi..."
    :columns="[
        ['label' => '#', 'data' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false, 'class' => 'w-12'],
        ['label' => 'Judul Aplikasi', 'data' => 'judul_aplikasi'],
        ['label' => 'Sub Judul Aplikasi', 'data' => 'sub_judul_aplikasi'],
        ['label' => 'Link Aplikasi', 'data' => 'link_aplikasi'],
        ['label' => 'Gambar Aplikasi', 'data' => 'image_aplikasi', 'name' => 'image_aplikasi', 'orderable' => false, 'searchable' => false, 'raw' => true],
        ['label' => 'Aksi', 'data' => 'opsi', 'orderable' => false, 'searchable' => false, 'raw' => true, 'class' => 'w-24'],
    ]"
>
    <x-slot:notice>
        @include('layouts.webromadan_backend.session_notif')
    </x-slot:notice>
</x-data-table>
@endsection
