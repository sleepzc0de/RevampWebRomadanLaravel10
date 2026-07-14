@extends('layouts.webromadan_backend.master_layout')

@php
    $mediaItems = json_decode($sejarah->media ?? '[]', true) ?? [];
@endphp

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Edit Sejarah</h5>
    </div>

    @include('layouts.webromadan_backend.session_notif')

    <form action="{{ route('sejarah.update', encrypt($sejarah->id)) }}" method="post" enctype="multipart/form-data" autocomplete="off">
        @csrf
        @method('PUT')
        <div class="card-body">
            <x-form.field name="judul" label="Judul Sejarah" :value="$sejarah->judul" required maxlength="255" placeholder="Masukkan Judul Sejarah" />

            <x-form.textarea name="sejarah" label="Sejarah" :value="$sejarah->sejarah" required maxlength="10000" placeholder="Sejarah" rich id="ckeditor_classic_empty_sejarah" />

            @if(!empty($mediaItems))
                <div class="mb-4" x-data="{ all: true }">
                    <label class="form-label mb-1.5 block">Media Saat Ini</label>
                    <label class="mb-2 flex items-center gap-2 text-sm">
                        <input type="checkbox" class="form-check-input" x-model="all" @change="$el.closest('[x-data]').querySelectorAll('.keep-media-checkbox').forEach(cb => cb.checked = all)">
                        Pilih Semua
                    </label>

                    <div class="flex flex-wrap gap-3">
                        @foreach($mediaItems as $index => $item)
                            @if(($item['type'] ?? null) === 'image')
                                <div class="relative w-[150px]">
                                    <label class="absolute top-1 right-1 z-10 flex items-center rounded bg-white/80 p-1">
                                        <input type="checkbox" name="keep_media[]" value="{{ $index }}" class="keep-media-checkbox form-check-input" checked>
                                    </label>
                                    <img data-blob-src="{{ route('media.blob', ['romadan_gambar_web', $item['path']]) }}" alt="{{ $item['original_name'] ?? 'Image' }}" class="h-[120px] w-[150px] rounded-t-md object-cover" style="background:#eef1f4;">
                                    <div class="truncate rounded-b-md bg-slate-100 px-1.5 py-1 text-xs dark:bg-white/5">{{ $item['original_name'] ?? 'Image' }}</div>
                                </div>
                            @elseif(($item['type'] ?? null) === 'video')
                                <div class="relative flex items-center gap-2 rounded-md bg-slate-100 px-3 py-2 dark:bg-white/5">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="keep_media[]" value="{{ $index }}" class="keep-media-checkbox form-check-input" checked>
                                    </label>
                                    <i class="ph-video text-lg text-red-600"></i>
                                    <span class="text-sm">{{ $item['url'] }}</span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                    <span class="form-text">Centang kotak di sebelah item media untuk menyimpannya. Item yang tidak dicentang akan dihapus.</span>
                </div>
            @endif

            <x-form.file name="image" label="Gambar Utama" accept="image/jpeg,image/png,image/jpg" help="Format: JPEG, PNG, JPG. Ukuran maksimal: 20MB" />
            @if($sejarah->image)
                <div class="mb-4 -mt-3">
                    <img data-blob-src="{{ route('media.blob', ['romadan_gambar_web', $sejarah->image]) }}" alt="" class="img-thumbnail" style="max-width: 300px; max-height: 200px; background:#eef1f4;">
                </div>
            @endif

            <div class="mb-4" x-data="multiImagePreview()">
                <label class="form-label mb-1.5 block">Tambah Gambar Baru</label>
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
                <label class="form-label mb-1.5 block">Tambah URL Video Baru</label>
                <template x-for="(url, i) in urls" :key="i">
                    <div class="mb-2 flex gap-2">
                        <input type="url" name="video_urls[]" x-model="urls[i]" class="form-control" placeholder="Masukkan URL video (YouTube, Vimeo, dll)">
                        <button type="button" @click="urls.length > 1 ? urls.splice(i, 1) : urls[i] = ''" class="btn btn-outline-danger btn-icon"><i class="ph-trash"></i></button>
                    </div>
                </template>
                <button type="button" @click="urls.push('')" class="btn btn-sm btn-light">
                    <i class="ph-plus me-1"></i> Tambah URL Video
                </button>
                <span class="form-text block">Masukkan URL video dari YouTube, Vimeo, atau platform video lainnya.</span>
                @error('video_urls.*')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
        </div>

        <x-form.actions :back-route="route('sejarah.index')" submit-label="Update" />
    </form>
</div>
@endsection
