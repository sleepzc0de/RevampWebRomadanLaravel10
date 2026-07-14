@extends('layouts.webromadan_backend.master_layout')

@section('content')
<x-data-table
    title="Status Berita"
    :ajax="route('status.index')"
    :create-route="route('status.create')"
    create-label="Tambah Status"
    search-placeholder="Cari status..."
    :columns="[
        ['label' => '#', 'data' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false, 'class' => 'w-12'],
        ['label' => 'Nama Status', 'data' => 'nama_status'],
        ['label' => 'Aksi', 'data' => 'opsi', 'orderable' => false, 'searchable' => false, 'raw' => true, 'class' => 'w-24'],
    ]"
>
    <x-slot:notice>
        @include('layouts.webromadan_backend.session_notif')
    </x-slot:notice>
</x-data-table>
@endsection
