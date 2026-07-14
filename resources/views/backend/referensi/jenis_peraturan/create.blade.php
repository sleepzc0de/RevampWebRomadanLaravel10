@extends('layouts.webromadan_backend.master_layout')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Tambah Referensi Jenis Peraturan</h5>
    </div>

    @include('layouts.webromadan_backend.session_notif')

    <form action="{{ route('jenis-peraturan.store') }}" method="post" autocomplete="off">
        @csrf
        <div class="card-body">
            <x-form.field
                name="nama_jenis_peraturan"
                label="Nama Jenis Peraturan"
                required
                maxlength="255"
                placeholder="Masukkan Nama Jenis Peraturan, contoh: (PMK/PP/KMK/SE/KEP)"
            />
        </div>

        <x-form.actions :back-route="route('jenis-peraturan.index')" submit-label="Simpan" />
    </form>
</div>
@endsection
