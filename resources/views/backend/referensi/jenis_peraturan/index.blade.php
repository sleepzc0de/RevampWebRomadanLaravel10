@extends('layouts.webromadan_backend.master_layout')

@section('content')
<x-data-table
    title="Jenis Peraturan"
    :ajax="route('jenis-peraturan.index')"
    :create-route="route('jenis-peraturan.create')"
    create-label="Tambah Jenis Peraturan"
    search-placeholder="Cari jenis peraturan..."
    :columns="[
        ['label' => '#', 'data' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false, 'class' => 'w-12'],
        ['label' => 'Nama Jenis Peraturan', 'data' => 'nama_jenis_peraturan'],
        ['label' => 'Aksi', 'data' => 'opsi', 'orderable' => false, 'searchable' => false, 'raw' => true, 'class' => 'w-24'],
    ]"
>
    <x-slot:notice>
        @include('layouts.webromadan_backend.session_notif')
    </x-slot:notice>
</x-data-table>
@endsection
