@extends('layouts.webromadan_backend.master_layout')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Visi Misi Romadan</h5>
    </div>

    @include('layouts.webromadan_backend.session_notif')

    <form action="{{ route('visi-misi.store') }}" method="post" enctype="multipart/form-data" autocomplete="off">
        @csrf
        <div class="card-body">
            <x-form.field name="judul" label="Judul Visi Misi" required maxlength="255" placeholder="Masukkan Judul Visi Misi" />

            <x-form.textarea name="visi" label="Visi" required placeholder="Visi" rich id="ckeditor_classic_empty_visi" />

            <x-form.textarea name="misi" label="Misi" required placeholder="Misi" rich id="ckeditor_classic_empty_misi" />

            <x-form.file name="image" label="Gambar Utama" required accept="image/jpeg,image/png,image/jpg" />

            <div class="mb-4" x-data="multiImagePreview()">
                <label class="form-label mb-1.5 block">Gambar Tambahan</label>
                <input type="file" name="additional_images[]" multiple accept="image/jpeg,image/png,image/jpg"
                       @change="onChange($event)"
                       class="form-control {{ $errors->has('additional_images') ? 'is-invalid' : '' }}">
                <span class="form-text">Anda dapat mengunggah beberapa gambar tambahan (opsional)</span>

                <div class="flex flex-wrap gap-2 mt-2">
                    <template x-for="(p, i) in previews" :key="i">
                        <img :src="p.url" class="img-thumbnail h-[150px] w-[150px] object-cover">
                    </template>
                </div>

                @error('additional_images')<span class="invalid-feedback">{{ $message }}</span>@enderror
                @error('additional_images.*')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="mb-4" x-data="videoUrlPreview(@js(old('video_url')))">
                <label class="form-label mb-1.5 block">URL Video (Opsional)</label>
                <input type="url" name="video_url" x-model="url" placeholder="Masukkan URL video YouTube atau Vimeo"
                       class="form-control {{ $errors->has('video_url') ? 'is-invalid' : '' }}">
                <span class="form-text">Video ini akan muncul di slider bersama dengan gambar-gambar</span>
                @error('video_url')<span class="invalid-feedback">{{ $message }}</span>@enderror
                <div class="mt-3" x-show="url" x-cloak x-html="embedHtml"></div>
            </div>
        </div>

        <x-form.actions :back-route="route('visi-misi.index')" submit-label="Simpan" />
    </form>
</div>
@endsection
