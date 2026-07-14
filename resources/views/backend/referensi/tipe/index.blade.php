@extends('layouts.webromadan_backend.master_layout')

@section('content')
<x-data-table
    title="Tipe Publikasi"
    :ajax="route('tipe.index')"
    :create-route="route('tipe.create')"
    create-label="Tambah Tipe"
    search-placeholder="Cari tipe..."
    :columns="[
        ['label' => '#', 'data' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false, 'class' => 'w-12'],
        ['label' => 'Nama Tipe', 'data' => 'nama_tipe'],
        ['label' => 'Aksi', 'data' => 'opsi', 'orderable' => false, 'searchable' => false, 'raw' => true, 'class' => 'w-24'],
    ]"
>
    <x-slot:notice>
        @include('layouts.webromadan_backend.session_notif')
    </x-slot:notice>
</x-data-table>
@endsection
