@extends('layouts.webromadan_backend.master_layout')

@section('content')
<x-data-table
    title="Tautan Footer"
    :ajax="route('footer-link.index')"
    :create-route="route('footer-link.create')"
    create-label="Tambah Tautan"
    search-placeholder="Cari tautan..."
    :order="[3, 'asc']"
    :columns="[
        ['label' => '#', 'data' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false, 'class' => 'w-12'],
        ['label' => 'Label', 'data' => 'label'],
        ['label' => 'URL', 'data' => 'url'],
        ['label' => 'Urutan', 'data' => 'sort_order'],
        ['label' => 'Status', 'data' => 'status', 'name' => 'is_active', 'orderable' => false, 'searchable' => false, 'raw' => true],
        ['label' => 'Aksi', 'data' => 'opsi', 'orderable' => false, 'searchable' => false, 'raw' => true, 'class' => 'w-24'],
    ]"
>
    <x-slot:notice>
        @include('layouts.webromadan_backend.session_notif')
        <p class="mb-0 px-5 pt-4 text-sm text-slate-500 dark:text-slate-400">
            Daftar tautan yang tampil pada bagian "Tautan" di footer seluruh halaman frontend. Urutkan dengan angka pada kolom Urutan (semakin kecil semakin awal tampil).
        </p>
    </x-slot:notice>
</x-data-table>
@endsection
