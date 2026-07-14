@extends('layouts.webromadan_backend.master_layout')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Tambah Aplikasi</h5>
    </div>

    @include('layouts.webromadan_backend.session_notif')

    <form action="{{ route('aplikasi.store') }}" method="post" enctype="multipart/form-data" autocomplete="off">
        @csrf
        <div class="card-body">
            <x-form.field name="judul_aplikasi" label="Judul Aplikasi" required maxlength="100" />

            <x-form.field name="sub_judul_aplikasi" label="Sub Judul Aplikasi" required maxlength="100" />

            <x-form.field type="url" name="link_aplikasi" label="Link Aplikasi" required maxlength="1000" />

            <x-form.file name="image" label="Gambar Aplikasi" required accept="image/jpeg,image/png,image/jpg" />
        </div>

        <x-form.actions :back-route="route('aplikasi.index')" submit-label="Simpan" />
    </form>
</div>
@endsection
