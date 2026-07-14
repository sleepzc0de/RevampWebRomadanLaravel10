@extends('layouts.webromadan_backend.master_layout')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Sejarah Romadan</h5>
    </div>

    @include('layouts.webromadan_backend.session_notif')

    <form action="{{ route('sejarah.store') }}" method="post" enctype="multipart/form-data" autocomplete="off">
        @csrf
        <div class="card-body">
            <x-form.field name="judul" label="Judul Sejarah" required maxlength="255" placeholder="Masukkan Judul Sejarah" />

            <x-form.textarea name="sejarah" label="Sejarah" required maxlength="10000" placeholder="Sejarah" rich id="ckeditor_classic_empty_sejarah" />

            <x-form.file name="image" label="Gambar Utama" accept="image/jpeg,image/png,image/jpg" help="Format: JPEG, PNG, JPG. Ukuran maksimal: 20MB" />

            <div class="mb-4" x-data="multiImagePreview()">
                <label class="form-label mb-1.5 block">Gambar Tambahan</label>
                <input type="file" name="images[]" multiple accept="image/jpeg,image/png,image/jpg"
                       @change="onChange($event)"
                       class="form-control {{ $errors->has('images') ? 'is-invalid' : '' }}">
                <span class="form-text">Pilih beberapa file gambar. Format: JPEG, PNG, JPG. Ukuran maksimal: 20MB per gambar</span>

                <div class="flex flex-wrap gap-2 mt-2">
                    <template x-for="(p, i) in previews" :key="i">
                        <img :src="p.url" class="img-thumbnail h-[150px] w-[150px] object-cover">
                    </template>
                </div>

                @error('images.*')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="mb-4" x-data="{ urls: [''] }">
                <label class="form-label mb-1.5 block">URL Video</label>
                <template x-for="(url, i) in urls" :key="i">
                    <div class="mb-2 flex gap-2">
                        <input type="url" name="video_urls[]" x-model="urls[i]" class="form-control" placeholder="Masukkan URL video (YouTube, Vimeo, dll)">
                        <button type="button" x-show="i > 0" @click="urls.splice(i, 1)" class="btn btn-outline-danger btn-icon"><i class="ph-trash"></i></button>
                    </div>
                </template>
                <button type="button" @click="urls.push('')" class="btn btn-sm btn-light">
                    <i class="ph-plus me-1"></i> Tambah URL Video
                </button>
                <span class="form-text block">Masukkan URL video dari YouTube, Vimeo, atau platform video lainnya.</span>
                @error('video_urls.*')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
        </div>

        <x-form.actions :back-route="route('sejarah.index')" submit-label="Simpan" />
    </form>
</div>
@endsection
