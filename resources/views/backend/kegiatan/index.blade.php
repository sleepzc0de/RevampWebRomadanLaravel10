@extends('layouts.webromadan_backend.master_layout')

@section('content')
<x-data-table
    title="Kegiatan"
    :ajax="route('kegiatan.index')"
    :create-route="route('kegiatan.create')"
    create-label="Tambah Kegiatan"
    search-placeholder="Cari kegiatan..."
    :columns="[
        ['label' => '#', 'data' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false, 'class' => 'w-12'],
        ['label' => 'Judul', 'data' => 'judul'],
        ['label' => 'Tempat', 'data' => 'tempat'],
        ['label' => 'File', 'data' => 'file_kegiatan', 'name' => 'file_kegiatan', 'orderable' => false, 'searchable' => false, 'raw' => true],
        ['label' => 'Gambar', 'data' => 'image_kegiatan', 'name' => 'image_kegiatan', 'orderable' => false, 'searchable' => false, 'raw' => true],
        ['label' => 'Tanggal Mulai', 'data' => 'tanggal_mulai'],
        ['label' => 'Tanggal Selesai', 'data' => 'tanggal_selesai'],
        ['label' => 'Slug', 'data' => 'slug', 'class' => 'max-w-[10rem] truncate'],
        ['label' => 'Aksi', 'data' => 'opsi', 'orderable' => false, 'searchable' => false, 'raw' => true, 'class' => 'w-24'],
    ]"
>
    <x-slot:notice>
        @include('layouts.webromadan_backend.session_notif')
    </x-slot:notice>
</x-data-table>
@endsection
