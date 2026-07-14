@extends('layouts.webromadan_backend.master_layout')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Edit Visi Misi</h5>
    </div>

    @include('layouts.webromadan_backend.session_notif')

    <form action="{{ route('visi-misi.update', encrypt($visimisi->id)) }}" method="post" enctype="multipart/form-data" autocomplete="off">
        @csrf
        @method('PUT')
        <div class="card-body">
            <x-form.field name="judul" label="Judul Visi Misi" :value="$visimisi->judul" required maxlength="255" placeholder="Masukkan Judul Artikel" />

            <x-form.textarea name="visi" label="Visi" :value="$visimisi->visi" required placeholder="Visi" rich id="ckeditor_classic_empty_visi" />

            <x-form.textarea name="misi" label="Misi" :value="$visimisi->misi" required placeholder="Misi" rich id="ckeditor_classic_empty_misi" />

            <x-form.file name="image" label="Gambar Utama" accept="image/jpeg,image/png,image/jpg" help="Kosongkan jika tidak ingin mengubah gambar utama" />
            <div class="mb-4 -mt-3">
                <img data-blob-src="{{ route('media.blob', ['romadan_gambar_web', $visimisi->image]) }}" alt="" class="img-thumbnail" style="width: 300px; background:#eef1f4;">
            </div>

            <div class="mb-4" x-data="multiImagePreview()">
                <label class="form-label mb-1.5 block">Gambar Tambahan</label>
                <input type="file" name="additional_images[]" multiple accept="image/jpeg,image/png,image/jpg"
                       @change="onChange($event)"
                       class="form-control {{ $errors->has('additional_images') ? 'is-invalid' : '' }}">
                <span class="form-text">Anda dapat menambahkan gambar-gambar baru</span>

                <div class="flex flex-wrap gap-2 mt-2">
                    <template x-for="(p, i) in previews" :key="i">
                        <img :src="p.url" class="img-thumbnail h-[150px] w-[150px] object-cover">
                    </template>
                </div>

                @error('additional_images')<span class="invalid-feedback">{{ $message }}</span>@enderror
                @error('additional_images.*')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            @if($visimisi->images && $visimisi->images->count() > 0)
                <div class="mb-4">
                    <p class="mb-2 text-[12.5px] font-semibold text-slate-600 dark:text-slate-300">Gambar Tambahan Saat Ini</p>
                    <div class="alert alert-info">
                        <small>Anda dapat mengurutkan gambar dengan nomor urut atau menghapus gambar dengan mencentang checkbox hapus.</small>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        @foreach($visimisi->images as $image)
                            <div class="relative">
                                <div class="absolute top-1 right-1 z-10 flex items-center gap-1 rounded bg-black/50 px-1.5 py-0.5 text-xs text-white">
                                    <input type="checkbox" class="form-check-input" name="delete_image[{{ $image->id }}]" value="1" id="delete_{{ $image->id }}">
                                    <label class="cursor-pointer" for="delete_{{ $image->id }}">Hapus</label>
                                </div>
                                <img data-blob-src="{{ route('media.blob', ['romadan_gambar_web', $image->image]) }}" alt="Gambar Tambahan" class="img-thumbnail h-[150px] w-[150px] object-cover" style="background:#eef1f4;">
                                <div class="mt-1">
                                    <label for="sort_{{ $image->id }}" class="form-label block text-xs">Urutan:</label>
                                    <input type="number" class="form-control form-control-sm w-16" id="sort_{{ $image->id }}" name="sort_order[{{ $image->id }}]" value="{{ $image->sort_order }}" min="0">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="mb-4" x-data="videoUrlPreview(@js(old('video_url', $visimisi->video_url)))">
                <label class="form-label mb-1.5 block">URL Video (Opsional)</label>
                <input type="url" name="video_url" x-model="url" placeholder="Masukkan URL video YouTube atau Vimeo"
                       class="form-control {{ $errors->has('video_url') ? 'is-invalid' : '' }}">
                <span class="form-text">Video ini akan muncul di slider bersama dengan gambar-gambar</span>
                @error('video_url')<span class="invalid-feedback">{{ $message }}</span>@enderror
                <div class="mt-3" x-show="url" x-cloak x-html="embedHtml"></div>
            </div>
        </div>

        <x-form.actions :back-route="route('visi-misi.index')" submit-label="Update" />
    </form>
</div>
@endsection
