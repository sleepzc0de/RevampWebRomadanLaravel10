@extends('layouts.webromadan_backend.master_layout')

@section('content')
<x-data-table
    title="Status Peraturan"
    :ajax="route('status-peraturan.index')"
    :create-route="route('status-peraturan.create')"
    create-label="Tambah Status Peraturan"
    search-placeholder="Cari status peraturan..."
    :columns="[
        ['label' => '#', 'data' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false, 'class' => 'w-12'],
        ['label' => 'Nama Status Peraturan', 'data' => 'nama_peraturan_status'],
        ['label' => 'Aksi', 'data' => 'opsi', 'orderable' => false, 'searchable' => false, 'raw' => true, 'class' => 'w-24'],
    ]"
>
    <x-slot:notice>
        @include('layouts.webromadan_backend.session_notif')
    </x-slot:notice>
</x-data-table>
@endsection
