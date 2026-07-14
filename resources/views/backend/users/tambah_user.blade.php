@extends('layouts.webromadan_backend.master_layout')

@php
    $roleOptions = collect($roles)->mapWithKeys(fn ($role, $i) => [$role->id => ($i + 1).' - '.$role->name])->all();
@endphp

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Tambah User Website Romadan</h5>
    </div>

    @include('layouts.webromadan_backend.session_notif')

    <form action="{{ route('users.store') }}" method="post" autocomplete="off">
        @csrf
        <div class="card-body">
            <x-form.field name="name" label="Nama" required maxlength="100" placeholder="Nama" />

            <x-form.field type="email" name="email" label="Email" required placeholder="Email" />

            <x-form.select name="role" label="Role" :options="$roleOptions" placeholder="-- Pilih Role --" required />

            <x-form.field type="password" name="password" label="Password" required placeholder="Password" help="Password harus memiliki minimal 8 karakter dan mengandung kombinasi huruf besar kecil, angka, dan simbol." />

            <x-form.field type="password" name="password_confirmation" label="Konfirmasi Password" required placeholder="Konfirmasi Password" />
        </div>

        <x-form.actions :back-route="route('users.index')" submit-label="Simpan" />
    </form>
</div>
@endsection
