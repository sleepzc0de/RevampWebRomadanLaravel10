@extends('layouts.webromadan_backend.master_layout')

@php
    $kategoriOptions = collect($kategori)->mapWithKeys(fn ($item) => [$item->id_kategori => $item->nama_kategori])->all();
@endphp

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Edit Pedoman</h5>
    </div>

    @include('layouts.webromadan_backend.session_notif')

    <form action="{{ route('pedoman.update', encrypt($pedoman->id)) }}" method="post" enctype="multipart/form-data" autocomplete="off">
        @csrf
        @method('PUT')
        <div class="card-body">
            <x-form.field name="judul_pedoman" label="Judul Pedoman" :value="$pedoman->judul_pedoman" required maxlength="255" placeholder="Masukkan Judul Pedoman" />

            <x-form.textarea name="deskripsi" label="Deskripsi" :value="$pedoman->deskripsi" maxlength="1000" placeholder="Deskripsi singkat pedoman (opsional)" />

            <x-form.select name="kategori" label="Kategori" :options="$kategoriOptions" :value="$pedoman->kategori" placeholder="-- Pilih Kategori (opsional) --" />

            <x-form.field type="date" name="tanggal_terbit" label="Tanggal Terbit" :value="$pedoman->tanggal_terbit" />

            <x-form.field type="file" name="file" label="File Pedoman" help="Kosongkan jika tidak ingin mengganti file. Format: doc, docx, ppt, pptx, csv, xls, xlsx, pdf, zip, rar. Maks 20MB." />
            <div class="mb-4 -mt-3">
                <a href="{{ asset('storage/romadan_file_web/'.$pedoman->file) }}" target="_blank" class="btn btn-sm btn-outline-info">
                    <i class="ph-file-text"></i> Lihat File Saat Ini
                </a>
            </div>
        </div>

        <x-form.actions :back-route="route('pedoman.index')" back-label="Kembali" submit-label="Update" :show-reset="false" />
    </form>
</div>
@endsection
