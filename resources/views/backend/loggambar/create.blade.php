@extends('layouts.webromadan_backend.master_layout')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Tambah Login Gambar Romadan</h5>
    </div>

    @include('layouts.webromadan_backend.session_notif')

    <form action="{{ route('loggambar.store') }}" method="post" enctype="multipart/form-data" autocomplete="off">
        @csrf
        <div class="card-body">
            <x-form.field name="nama_gambar" label="Nama Gambar" required maxlength="255" placeholder="Masukkan Nama Gambar" />

            <x-form.file name="image" label="Gambar Login" required accept="image/jpeg,image/png,image/jpg" />
        </div>

        <x-form.actions :back-route="route('loggambar.index')" submit-label="Simpan" />
    </form>
</div>
@endsection
