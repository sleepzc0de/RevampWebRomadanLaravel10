@extends('layouts.webromadan_backend.master_layout')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Tentang Romadan</h5>
    </div>

    @include('layouts.webromadan_backend.session_notif')

    <form action="{{ route('tentang.store') }}" method="post" enctype="multipart/form-data" autocomplete="off">
        @csrf
        <div class="card-body">
            <x-form.field name="judul" label="Judul Tentang" required maxlength="255" placeholder="Masukkan Judul tentang" />

            <x-form.textarea name="tentang" label="Tentang" required maxlength="1000" placeholder="Tentang" rich id="ckeditor_classic_empty_tentang" />

            <x-form.file name="image" label="Gambar Utama" required accept="image/jpeg,image/png,image/jpg" help="Format: JPG, JPEG, PNG. Maksimal: 20MB" />

            <div class="mb-4" x-data="multiImagePreview()">
                <label class="form-label mb-1.5 block">Gambar Tambahan</label>
                <input type="file" name="additional_images[]" multiple accept="image/jpeg,image/png,image/jpg"
                       @change="onChange($event)"
                       class="form-control {{ $errors->has('additional_images') ? 'is-invalid' : '' }}">
                <span class="form-text">Format: JPG, JPEG, PNG. Maksimal 20MB per file. Anda dapat memilih beberapa file.</span>

                <div class="flex flex-wrap gap-2 mt-2">
                    <template x-for="(p, i) in previews" :key="i">
                        <img :src="p.url" class="img-thumbnail h-[150px] w-[150px] object-cover">
                    </template>
                </div>

                @error('additional_images.*')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="mb-4" x-data="videoUrlPreview(@js(old('video_url')))">
                <label class="form-label mb-1.5 block">URL Video</label>
                <input type="url" name="video_url" x-model="url" placeholder="Contoh: https://www.youtube.com/watch?v=example"
                       class="form-control {{ $errors->has('video_url') ? 'is-invalid' : '' }}">
                <span class="form-text">Masukkan URL video dari platform seperti YouTube atau Vimeo.</span>
                @error('video_url')<span class="invalid-feedback">{{ $message }}</span>@enderror
                <div class="mt-3" x-show="url" x-cloak x-html="embedHtml"></div>
            </div>
        </div>

        <x-form.actions :back-route="route('tentang.index')" submit-label="Simpan" />
    </form>
</div>
@endsection
