@extends('layouts.webromadan_backend.master_layout')

@section('content')
<x-data-table
    title="Informasi Publik"
    :ajax="route('informasi-publik.index')"
    :create-route="count($data) < 3 ? route('informasi-publik.create') : null"
    create-label="Tambah Informasi Publik"
    search-placeholder="Cari informasi..."
    :columns="[
        ['label' => '#', 'data' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false, 'class' => 'w-12'],
        ['label' => 'Judul Informasi', 'data' => 'judul_list_informasi'],
        ['label' => 'Isi Informasi', 'data' => 'isi_list_informasi', 'class' => 'max-w-xs truncate'],
        ['label' => 'Link Informasi', 'data' => 'link_list_informasi'],
        ['label' => 'Aksi', 'data' => 'opsi', 'orderable' => false, 'searchable' => false, 'raw' => true, 'class' => 'w-24'],
    ]"
>
    <x-slot:notice>
        @include('layouts.webromadan_backend.session_notif')
    </x-slot:notice>
</x-data-table>

<x-data-table
    title="Informasi Publik Home"
    :ajax="route('informasi-publik.index-home')"
    :create-route="count($data2) < 1 ? route('informasi-publik.create-home') : null"
    create-label="Tambah InfoPublik Home"
    search-placeholder="Cari..."
    :columns="[
        ['label' => '#', 'data' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false, 'class' => 'w-12'],
        ['label' => 'Judul', 'data' => 'judul', 'class' => 'max-w-xs truncate'],
        ['label' => 'Isi', 'data' => 'isi', 'class' => 'max-w-xs truncate'],
        ['label' => 'Aksi', 'data' => 'opsi', 'orderable' => false, 'searchable' => false, 'raw' => true, 'class' => 'w-24'],
    ]"
/>
@endsection
