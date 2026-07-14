@extends('layouts.webromadan_backend.master_layout')

@section('content')
<x-data-table
    title="User Manajemen"
    :ajax="route('users.index')"
    :create-route="route('users.create')"
    create-label="Tambah Data"
    search-placeholder="Cari user..."
    :columns="[
        ['label' => '#', 'data' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false, 'class' => 'w-12'],
        ['label' => 'Nama', 'data' => 'name'],
        ['label' => 'Email', 'data' => 'email'],
        ['label' => 'Role', 'data' => 'role_name'],
        ['label' => 'Aksi', 'data' => 'opsi', 'orderable' => false, 'searchable' => false, 'raw' => true, 'class' => 'w-24'],
    ]"
>
    <x-slot:notice>
        @include('layouts.webromadan_backend.session_notif')
    </x-slot:notice>
</x-data-table>
@endsection
