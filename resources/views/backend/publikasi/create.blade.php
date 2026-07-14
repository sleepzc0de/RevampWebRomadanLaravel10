@extends('layouts.webromadan_backend.master_layout')

@php
    $tipeOptions = collect($tipe)->mapWithKeys(fn ($item, $i) => [$item->id_tipe => ($i + 1).' - '.$item->nama_tipe])->all();
    $kategoriOptions = collect($kategori)->mapWithKeys(fn ($item, $i) => [$item->id_kategori => ($i + 1).' - '.$item->nama_kategori])->all();
@endphp

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Tambah Publikasi</h5>
    </div>

    @include('layouts.webromadan_backend.session_notif')

    <form action="{{ route('publikasi.store') }}" method="post" enctype="multipart/form-data" autocomplete="off">
        @csrf
        <div class="card-body">
            <x-form.field name="judul" label="Judul Publikasi" required maxlength="255" placeholder="Masukkan Judul publikasi" />

            <x-form.field name="sub_judul" label="Sub Judul Publikasi" required maxlength="255" placeholder="Masukkan Sub Judul publikasi" />

            <x-form.select name="tipe" label="Tipe Publikasi" :options="$tipeOptions" placeholder="-- Pilih Tipe --" required />

            <x-form.select name="kategori" label="Kategori Publikasi" :options="$kategoriOptions" placeholder="-- Pilih Kategori --" required />

            <div class="mb-4" x-data="multiImagePreview()">
                <label class="form-label mb-1.5 block">Gambar Publikasi <span class="text-danger">*</span></label>
                <input type="file" name="images[]" multiple required accept="image/jpeg,image/png,image/jpg"
                       @change="onChange($event)"
                       class="form-control {{ $errors->has('images') ? 'is-invalid' : '' }}">
                <span class="form-text">Anda dapat memilih beberapa gambar sekaligus. Gambar pertama akan menjadi gambar utama.</span>

                <div class="flex flex-wrap gap-2 mt-2">
                    <template x-for="(p, i) in previews" :key="i">
                        <div class="relative">
                            <img :src="p.url" class="img-thumbnail h-[150px] w-[150px] object-cover">
                            <span x-show="p.primary" class="badge bg-primary absolute top-0 start-0">Utama</span>
                        </div>
                    </template>
                </div>

                @error('images')<span class="invalid-feedback">{{ $message }}</span>@enderror
                @error('images.*')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <x-form.field type="file" name="file" label="File Publikasi" accept=".pdf,.doc,.docx" />

            <x-form.textarea name="isi" label="Isi Publikasi" required maxlength="25000" placeholder="Isi publikasi" rich id="ckeditor_classic_empty" />

            <x-form.field
                type="url"
                name="embedded_media"
                label="URL Media (Video/Image)"
                placeholder="https://youtube.com/watch?v=example or image URL"
                help="Masukkan URL YouTube, Vimeo, atau gambar yang ingin ditampilkan"
            />

            <x-form.field
                type="datetime-local"
                name="publish_at"
                label="Jadwalkan Publikasi"
                help="Kosongkan untuk simpan sebagai draft. Isi tanggal & jam untuk otomatis dipublikasikan pada waktu tersebut."
            />
        </div>

        <x-form.actions :back-route="route('publikasi.index')" submit-label="Simpan" />
    </form>
</div>
@endsection
