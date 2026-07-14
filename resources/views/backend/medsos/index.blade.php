@extends('layouts.webromadan_backend.master_layout')

@section('content')
<x-data-table
    title="Media Sosial"
    :ajax="route('medsos.index')"
    :create-route="count($data) < 5 ? route('medsos.create') : null"
    create-label="Tambah Medsos"
    search-placeholder="Cari medsos..."
    :columns="[
        ['label' => '#', 'data' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false, 'class' => 'w-12'],
        ['label' => 'Nama Medsos', 'data' => 'nama_medsos'],
        ['label' => 'Link Medsos', 'data' => 'link_medsos'],
        ['label' => 'Logo Medsos', 'data' => 'logo_medsos'],
        ['label' => 'Aksi', 'data' => 'opsi', 'orderable' => false, 'searchable' => false, 'raw' => true, 'class' => 'w-24'],
    ]"
>
    <x-slot:notice>
        @include('layouts.webromadan_backend.session_notif')
    </x-slot:notice>
</x-data-table>
@endsection
