@extends('layouts.webromadan_backend.master_layout')

@section('content')
<x-data-table
    title="Layanan"
    :ajax="route('layanan.index')"
    :create-route="route('layanan.create')"
    create-label="Tambah Layanan"
    search-placeholder="Cari layanan..."
    :columns="[
        ['label' => '#', 'data' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false, 'class' => 'w-12'],
        ['label' => 'Judul', 'data' => 'judul'],
        ['label' => 'Gambar', 'data' => 'image_layanan', 'name' => 'image_layanan', 'orderable' => false, 'searchable' => false, 'raw' => true],
        ['label' => 'Aksi', 'data' => 'opsi', 'orderable' => false, 'searchable' => false, 'raw' => true, 'class' => 'w-24'],
    ]"
>
    <x-slot:notice>
        @include('layouts.webromadan_backend.session_notif')
    </x-slot:notice>
</x-data-table>
@endsection
