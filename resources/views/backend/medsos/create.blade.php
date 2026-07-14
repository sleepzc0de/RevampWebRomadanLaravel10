@extends('layouts.webromadan_backend.master_layout')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Tambah Medsos Romadan</h5>
    </div>

    @include('layouts.webromadan_backend.session_notif')

    <form action="{{ route('medsos.store') }}" method="post" autocomplete="off">
        @csrf
        <div class="card-body">
            <x-form.field name="nama_medsos" label="Nama Medsos" required maxlength="255" placeholder="Masukkan Nama Medsos" />

            <x-form.field name="link_medsos" label="Link Medsos" required maxlength="1000" placeholder="Masukkan Link Medsos" />

            <x-form.field name="logo_medsos" label="Logo Medsos" required maxlength="255" placeholder="Masukkan Nama Logo Medsos dari Fontawesome, contoh: fa-brands fa-facebook" />
        </div>

        <x-form.actions :back-route="route('medsos.index')" submit-label="Simpan" />
    </form>
</div>
@endsection
