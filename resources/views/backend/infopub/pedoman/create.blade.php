@extends('layouts.webromadan_backend.master_layout')

@php
    $kategoriOptions = collect($kategori)->mapWithKeys(fn ($item) => [$item->id_kategori => $item->nama_kategori])->all();
@endphp

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Tambah Pedoman</h5>
    </div>

    @include('layouts.webromadan_backend.session_notif')

    <form action="{{ route('pedoman.store') }}" method="post" enctype="multipart/form-data" autocomplete="off">
        @csrf
        <div class="card-body">
            <x-form.field name="judul_pedoman" label="Judul Pedoman" required maxlength="255" placeholder="Masukkan Judul Pedoman" />

            <x-form.textarea name="deskripsi" label="Deskripsi" maxlength="1000" placeholder="Deskripsi singkat pedoman (opsional)" />

            <x-form.select name="kategori" label="Kategori" :options="$kategoriOptions" placeholder="-- Pilih Kategori (opsional) --" />

            <x-form.field type="date" name="tanggal_terbit" label="Tanggal Terbit" />

            <x-form.field type="file" name="file" label="File Pedoman" required accept=".doc,.docx,.ppt,.pptx,.csv,.xls,.xlsx,.pdf,.zip,.rar" help="Format: doc, docx, ppt, pptx, csv, xls, xlsx, pdf, zip, rar. Maks 20MB." />
        </div>

        <x-form.actions :back-route="route('pedoman.index')" submit-label="Simpan" />
    </form>
</div>
@endsection
