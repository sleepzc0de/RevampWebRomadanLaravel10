@extends('layouts.webromadan_backend.master_layout')

@section('content')
<x-data-table
    title="Visi dan Misi"
    :ajax="route('visi-misi.index')"
    :create-route="count($data) < 1 ? route('visi-misi.create') : null"
    create-label="Tambah Visi dan Misi"
    search-placeholder="Cari..."
    :columns="[
        ['label' => '#', 'data' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false, 'class' => 'w-12'],
        ['label' => 'Judul', 'data' => 'judul'],
        ['label' => 'Visi', 'data' => 'visi', 'class' => 'max-w-xs truncate'],
        ['label' => 'Misi', 'data' => 'misi', 'class' => 'max-w-xs truncate'],
        ['label' => 'Gambar', 'data' => 'image_visimisi', 'name' => 'image_visimisi', 'orderable' => false, 'searchable' => false, 'raw' => true],
        ['label' => 'Aksi', 'data' => 'opsi', 'orderable' => false, 'searchable' => false, 'raw' => true, 'class' => 'w-24'],
    ]"
>
    <x-slot:notice>
        @include('layouts.webromadan_backend.session_notif')
    </x-slot:notice>
</x-data-table>
@endsection
