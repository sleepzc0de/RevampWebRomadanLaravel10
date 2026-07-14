@extends('layouts.webromadan_backend.master_layout')

@php
    $roleOptions = collect($roles)->mapWithKeys(fn ($role) => [$role->id => $role->name])->all();
@endphp

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Edit User</h5>
    </div>

    @include('layouts.webromadan_backend.session_notif')

    <form action="{{ route('users.update', Crypt::encrypt($user->id)) }}" method="post" autocomplete="off">
        @csrf
        @method('PUT')
        <div class="card-body">
            <x-form.field name="name" label="Nama" :value="$user->name" required />

            <x-form.field type="email" name="email" label="Email" :value="$user->email" required />

            <x-form.field type="password" name="password" label="Password" help="Biarkan kosong jika tidak ingin mengubah password" />

            <x-form.select name="role" label="Role" :options="$roleOptions" :value="$userRole?->id" required />
        </div>

        <x-form.actions :back-route="route('users.index')" submit-label="Simpan Perubahan" :show-reset="false" />
    </form>
</div>
@endsection
