@extends('layouts.webromadan_backend.master_layout')

@section('content')
<x-data-table
    title="Tentang Biro"
    :ajax="route('tentang.index')"
    :create-route="count($data) < 1 ? route('tentang.create') : null"
    create-label="Tambah Tentang"
    search-placeholder="Cari..."
    :columns="[
        ['label' => '#', 'data' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false, 'class' => 'w-12'],
        ['label' => 'Judul', 'data' => 'judul'],
        ['label' => 'Tentang', 'data' => 'tentang', 'class' => 'max-w-xs truncate'],
        ['label' => 'Excerpt', 'data' => 'excerpt', 'class' => 'max-w-xs truncate'],
        ['label' => 'Gambar', 'data' => 'image_tentang', 'name' => 'image_tentang', 'orderable' => false, 'searchable' => false, 'raw' => true],
        ['label' => 'Aksi', 'data' => 'opsi', 'orderable' => false, 'searchable' => false, 'raw' => true, 'class' => 'w-24'],
    ]"
>
    <x-slot:notice>
        @include('layouts.webromadan_backend.session_notif')
    </x-slot:notice>
</x-data-table>
@endsection
