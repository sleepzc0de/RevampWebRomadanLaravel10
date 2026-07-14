@extends('layouts.webromadan_backend.master_layout')

@section('content')
<x-data-table
    title="FAQ"
    :ajax="route('faq.index')"
    :create-route="route('faq.create')"
    create-label="Tambah FAQ"
    search-placeholder="Cari FAQ..."
    :columns="[
        ['label' => '#', 'data' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false, 'class' => 'w-12'],
        ['label' => 'Judul FAQ', 'data' => 'faq_judul'],
        ['label' => 'Isi FAQ', 'data' => 'faq_isi'],
        ['label' => 'Kategori', 'data' => 'kategori.nama_kategori', 'name' => 'kategori.nama_kategori', 'orderable' => false, 'searchable' => false],
        ['label' => 'Penulis', 'data' => 'penulis'],
        ['label' => 'Aksi', 'data' => 'opsi', 'orderable' => false, 'searchable' => false, 'raw' => true, 'class' => 'w-24'],
    ]"
>
    <x-slot:notice>
        @include('layouts.webromadan_backend.session_notif')
    </x-slot:notice>
</x-data-table>
@endsection
