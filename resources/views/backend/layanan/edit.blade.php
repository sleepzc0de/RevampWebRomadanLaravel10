@extends('layouts.webromadan_backend.master_layout')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Edit Layanan</h5>
    </div>

    @include('layouts.webromadan_backend.session_notif')

    <form action="{{ route('layanan.update', encrypt($layanan->id)) }}" method="post" enctype="multipart/form-data" autocomplete="off">
        @csrf
        @method('PUT')
        <div class="card-body">
            <x-form.field name="judul" label="Judul Layanan" :value="$layanan->judul" required maxlength="255" placeholder="Masukkan Judul Layanan" />

            <x-form.textarea name="layanan" label="Layanan" :value="$layanan->layanan" required maxlength="5000" placeholder="layanan" rich id="ckeditor_classic_empty_layanan" help="Maksimal 5000 karakter" />

            <x-form.file name="image" label="Gambar Utama" accept="image/jpeg,image/png,image/jpg" help="Maksimal 20MB (JPEG, PNG, JPG)" />
            <div class="mb-4 -mt-3">
                <img data-blob-src="{{ route('media.blob', ['romadan_gambar_web', $layanan->image]) }}" alt="" class="img-thumbnail" style="width: 300px; background:#eef1f4;">
            </div>

            <div class="mb-4" x-data="multiImagePreview()">
                <label class="form-label mb-1.5 block">Gambar Tambahan</label>
                <input type="file" name="additional_images[]" multiple accept="image/jpeg,image/png,image/jpg"
                       @change="onChange($event)"
                       class="form-control {{ $errors->has('additional_images') ? 'is-invalid' : '' }}">
                <span class="form-text">Anda dapat memilih beberapa gambar. Maksimal 20MB per gambar (JPEG, PNG, JPG)</span>

                <div class="flex flex-wrap gap-2 mt-2">
                    <template x-for="(p, i) in previews" :key="i">
                        <img :src="p.url" class="img-thumbnail h-[150px] w-[150px] object-cover">
                    </template>
                </div>

                @error('additional_images.*')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            @if($layanan->additionalImages && $layanan->additionalImages->count() > 0)
                <div class="mb-4">
                    <p class="mb-2 text-[12.5px] font-semibold text-slate-600 dark:text-slate-300">Gambar Tambahan yang Ada:</p>
                    <div class="flex flex-wrap gap-3">
                        @foreach($layanan->additionalImages as $image)
                            <div x-data="{ removed: false }" x-show="!removed" class="relative">
                                <img data-blob-src="{{ route('media.blob', ['romadan_gambar_web', $image->image_path]) }}" alt="Additional Image" class="img-thumbnail h-[150px] w-[150px] object-cover" style="background:#eef1f4;">
                                <button type="button" @click="removed = true"
                                    class="absolute right-1 top-1 flex h-5 w-5 items-center justify-center rounded-full bg-red-600/90 text-xs text-white">&times;</button>
                                <input type="checkbox" name="remove_images[]" value="{{ $image->id }}" x-model="removed" class="hidden">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="mb-4" x-data="videoUrlPreview(@js(old('video_url', $layanan->video_url)))">
                <label class="form-label mb-1.5 block">URL Video</label>
                <input type="url" name="video_url" x-model="url" placeholder="Contoh: https://www.youtube.com/watch?v=AbCdEfGhIjK"
                       class="form-control {{ $errors->has('video_url') ? 'is-invalid' : '' }}">
                <span class="form-text">Masukkan URL video dari platform seperti YouTube, Vimeo, dll.</span>
                @error('video_url')<span class="invalid-feedback">{{ $message }}</span>@enderror
                <div class="mt-3" x-show="url" x-cloak x-html="embedHtml"></div>
            </div>
        </div>

        <x-form.actions :back-route="route('layanan.index')" submit-label="Update" />
    </form>
</div>
@endsection
