@extends('layouts.webromadan_backend.master_layout')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Edit Tentang</h5>
    </div>

    @include('layouts.webromadan_backend.session_notif')

    <form action="{{ route('tentang.update', encrypt($tentang->id)) }}" method="post" enctype="multipart/form-data" autocomplete="off">
        @csrf
        @method('PUT')
        <div class="card-body">
            <x-form.field name="judul" label="Judul Tentang" :value="$tentang->judul" required maxlength="255" placeholder="Masukkan Judul Artikel" />

            <x-form.textarea name="tentang" label="Tentang" :value="$tentang->tentang" required maxlength="1000" placeholder="tentang" rich id="ckeditor_classic_empty_tentang" />

            <x-form.file name="image" label="Gambar Utama" accept="image/jpeg,image/png,image/jpg" help="Format: JPG, JPEG, PNG. Maksimal: 20MB" />
            <div class="mb-4 -mt-3">
                <img data-blob-src="{{ route('media.blob', ['romadan_gambar_web', $tentang->image]) }}" alt="Gambar Utama" class="img-thumbnail" style="width: 300px; background:#eef1f4;">
            </div>

            <div class="mb-4" x-data="multiImagePreview()">
                <label class="form-label mb-1.5 block">Gambar Tambahan Baru</label>
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

            @if(isset($tentang->additionalImages) && $tentang->additionalImages->count() > 0)
                <div class="mb-4" x-data="{ all: false }">
                    <label class="form-label mb-1.5 block">Gambar Tambahan Saat Ini</label>
                    <label class="mb-2 flex items-center gap-2 text-sm">
                        <input type="checkbox" class="form-check-input" x-model="all" @change="$el.closest('[x-data]').querySelectorAll('.remove-image-checkbox').forEach(cb => cb.checked = all)">
                        Pilih semua untuk dihapus
                    </label>
                    <div class="flex flex-wrap gap-3">
                        @foreach($tentang->additionalImages as $image)
                            <div class="relative">
                                <label class="absolute top-1 right-1 z-10 flex items-center gap-1 rounded bg-black/50 px-1.5 py-0.5 text-xs text-white">
                                    <input type="checkbox" name="remove_additional_image[]" value="{{ $image->id }}" class="remove-image-checkbox form-check-input">
                                    Hapus
                                </label>
                                <img data-blob-src="{{ route('media.blob', ['romadan_gambar_web', $image->image_path]) }}" alt="Additional Image" class="img-thumbnail h-[150px] w-[150px] object-cover" style="background:#eef1f4;">
                            </div>
                        @endforeach
                    </div>
                    <span class="form-text">Centang kotak "Hapus" pada gambar yang ingin dihapus.</span>
                </div>
            @endif

            <div class="mb-4" x-data="videoUrlPreview(@js(old('video_url', $tentang->video_url)))">
                <label class="form-label mb-1.5 block">URL Video</label>
                <input type="url" name="video_url" x-model="url" placeholder="Contoh: https://www.youtube.com/watch?v=example"
                       class="form-control {{ $errors->has('video_url') ? 'is-invalid' : '' }}">
                <span class="form-text">Masukkan URL video dari platform seperti YouTube atau Vimeo.</span>
                @error('video_url')<span class="invalid-feedback">{{ $message }}</span>@enderror
                <div class="mt-3" x-show="url" x-cloak x-html="embedHtml"></div>
            </div>
        </div>

        <x-form.actions :back-route="route('tentang.index')" submit-label="Update" />
    </form>
</div>
@endsection
