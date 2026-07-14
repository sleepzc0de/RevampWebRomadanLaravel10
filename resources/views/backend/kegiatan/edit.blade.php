@extends('layouts.webromadan_backend.master_layout')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Edit Kegiatan</h5>
    </div>

    @include('layouts.webromadan_backend.session_notif')

    <form action="{{ route('kegiatan.update', encrypt($kegiatan->id)) }}" method="post" enctype="multipart/form-data" autocomplete="off">
        @csrf
        @method('PUT')
        <div class="card-body">
            <x-form.field name="judul" label="Judul Kegiatan" :value="$kegiatan->judul" required placeholder="Masukkan Judul kegiatan" />

            <x-form.field name="tempat" label="Tempat Kegiatan" :value="$kegiatan->tempat" required placeholder="Masukkan tempat Kegiatan" />

            <x-form.file name="image" label="Gambar Kegiatan" accept="image/jpeg,image/png,image/jpg" />
            <div class="mb-4 -mt-3">
                <img data-blob-src="{{ route('media.blob', ['romadan_gambar_web', $kegiatan->image]) }}" alt="" class="img-thumbnail" style="width: 300px; background:#eef1f4;">
            </div>

            <x-form.textarea name="isi" label="Kegiatan" :value="$kegiatan->isi" required placeholder="isi kegiatan" rich id="ckeditor_classic_empty" />

            <x-form.field type="file" name="file" label="File Kegiatan" help="File saat ini: {{ $kegiatan->file }}" />

            <div x-data="{ tanggalMulai: @js(old('tanggal_mulai', $kegiatan->tanggal_mulai)) }">
                <x-form.field type="datetime-local" name="tanggal_mulai" label="Tanggal Mulai" :value="old('tanggal_mulai', $kegiatan->tanggal_mulai)" required x-on:change="tanggalMulai = $event.target.value" />
                <x-form.field type="datetime-local" name="tanggal_selesai" label="Tanggal Selesai" :value="old('tanggal_selesai', $kegiatan->tanggal_selesai)" x-bind:min="tanggalMulai" />
            </div>

            <x-form.field name="link" label="Link Absen" :value="$kegiatan->link" required placeholder="Masukkan Link Absen" />
        </div>

        <x-form.actions :back-route="route('kegiatan.index')" submit-label="Update" />
    </form>
</div>
@endsection
