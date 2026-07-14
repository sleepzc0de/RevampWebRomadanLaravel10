@extends('layouts.webromadan_backend.master_layout')

@section('content')
<x-data-table
    title="Sejarah"
    :ajax="route('sejarah.index')"
    :create-route="count($data) < 1 ? route('sejarah.create') : null"
    create-label="Tambah Sejarah"
    search-placeholder="Cari..."
    :columns="[
        ['label' => '#', 'data' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false, 'class' => 'w-12'],
        ['label' => 'Judul', 'data' => 'judul'],
        ['label' => 'Sejarah', 'data' => 'sejarah', 'class' => 'max-w-xs truncate'],
        ['label' => 'Gambar', 'data' => 'image_sejarah', 'name' => 'image_sejarah', 'orderable' => false, 'searchable' => false, 'raw' => true],
        ['label' => 'Aksi', 'data' => 'opsi', 'orderable' => false, 'searchable' => false, 'raw' => true, 'class' => 'w-24'],
    ]"
>
    <x-slot:notice>
        @include('layouts.webromadan_backend.session_notif')
    </x-slot:notice>
</x-data-table>
@endsection
