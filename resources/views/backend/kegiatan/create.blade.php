@extends('layouts.webromadan_backend.master_layout')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Kegiatan Romadan</h5>
    </div>

    @include('layouts.webromadan_backend.session_notif')

    <form action="{{ route('kegiatan.store') }}" method="post" enctype="multipart/form-data" autocomplete="off">
        @csrf
        <div class="card-body">
            <x-form.field name="judul" label="Judul Kegiatan" required placeholder="Masukkan Judul kegiatan" />

            <x-form.field name="tempat" label="Tempat Kegiatan" required placeholder="Masukkan tempat Kegiatan" />

            <x-form.file name="image" label="Gambar Kegiatan" required accept="image/jpeg,image/png,image/jpg" />

            <x-form.textarea name="isi" label="Kegiatan" required placeholder="isi kegiatan" rich id="ckeditor_classic_empty_kegiatan" />

            <x-form.field type="file" name="file" label="File Kegiatan" required accept=".doc,.docx,.ppt,.pptx,.csv,.xls,.xlsx,.pdf,.zip,.rar" />

            <div x-data="{ tanggalMulai: '' }">
                <x-form.field type="datetime-local" name="tanggal_mulai" label="Tanggal Mulai" required x-on:change="tanggalMulai = $event.target.value" />
                <x-form.field type="datetime-local" name="tanggal_selesai" label="Tanggal Selesai" x-bind:min="tanggalMulai" x-bind:disabled="!tanggalMulai" />
            </div>

            <x-form.field name="link" label="Link Absen" required placeholder="Masukkan Link Absen" />
        </div>

        <x-form.actions :back-route="route('kegiatan.index')" submit-label="Simpan" />
    </form>
</div>
@endsection
