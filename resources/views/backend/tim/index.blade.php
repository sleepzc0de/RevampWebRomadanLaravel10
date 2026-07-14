@extends('layouts.webromadan_backend.master_layout')

@section('content')
<x-data-table
    title="Tim Pengembang"
    :ajax="route('pengembang.index')"
    :create-route="route('pengembang.create')"
    create-label="Tambah Data"
    search-placeholder="Cari pengembang..."
    :columns="[
        ['label' => '#', 'data' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false, 'class' => 'w-12'],
        {{-- Fallback avatar sebagai data-URI SVG — tidak bergantung file di public/ --}}
        ['label' => 'Foto', 'data' => 'photo', 'orderable' => false, 'searchable' => false, 'image' => true, 'fallback' => 'data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 48 48%22%3E%3Crect width=%2248%22 height=%2248%22 fill=%22%23e2e8f0%22/%3E%3Ccircle cx=%2224%22 cy=%2218%22 r=%228%22 fill=%22%2394a3b8%22/%3E%3Cpath d=%22M8 44a16 16 0 0 1 32 0z%22 fill=%22%2394a3b8%22/%3E%3C/svg%3E'],
        ['label' => 'Nama', 'data' => 'name'],
        ['label' => 'Keahlian', 'data' => 'skill'],
        ['label' => 'Aksi', 'data' => 'opsi', 'orderable' => false, 'searchable' => false, 'raw' => true, 'class' => 'w-24'],
    ]"
>
    <x-slot:notice>
        @include('layouts.webromadan_backend.session_notif')
    </x-slot:notice>
</x-data-table>
@endsection
