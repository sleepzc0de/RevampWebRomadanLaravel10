@extends('layouts.webromadan_backend.master_layout')

@php
    $tipeOptions = collect($tipe)->mapWithKeys(fn ($item, $i) => [$item->id_tipe => ($i + 1).' - '.$item->nama_tipe])->all();
    $kategoriOptions = collect($kategori)->mapWithKeys(fn ($item, $i) => [$item->id_kategori => ($i + 1).' - '.$item->nama_kategori])->all();
    $statusOptions = collect($status)->mapWithKeys(fn ($item, $i) => [$item->nama_status => ($i + 1).' - '.$item->nama_status])->all();
    $currentStatus = old('status', $publikasi->status);
@endphp

@section('content')
<div class="card">
    <div class="card-header flex items-center justify-between">
        <h5 class="mb-0">Edit Publikasi</h5>
        <a href="{{ route('publikasi.revisions', encrypt($publikasi->id)) }}" class="btn btn-sm btn-outline-secondary">
            <i class="ph-clock-counter-clockwise"></i> Riwayat Revisi
        </a>
    </div>

    @include('layouts.webromadan_backend.session_notif')

    <form action="{{ route('publikasi.update', encrypt($publikasi->id)) }}" method="post" enctype="multipart/form-data" autocomplete="off">
        @csrf
        @method('PUT')
        <div class="card-body">
            <x-form.field name="judul" label="Judul Publikasi" :value="$publikasi->judul" required maxlength="255" placeholder="Masukkan Judul publikasi" />

            <x-form.field name="sub_judul" label="Sub Judul Publikasi" :value="$publikasi->sub_judul" required maxlength="255" placeholder="Masukkan Sub Judul publikasi" />

            <x-form.select name="tipe" label="Tipe Publikasi" :options="$tipeOptions" :value="$publikasi->tipe" placeholder="-- Pilih Tipe --" required />

            <x-form.select name="kategori" label="Kategori Publikasi" :options="$kategoriOptions" :value="$publikasi->kategori" placeholder="-- Pilih Kategori --" required />

            <div class="mb-4" x-data="{
                newPreviews: [],
                onNewImages(e) {
                    this.newPreviews.forEach((url) => URL.revokeObjectURL(url));
                    this.newPreviews = Array.from(e.target.files || []).map((file) => URL.createObjectURL(file));
                },
                guardPrimaryDelete(e, isPrimary) {
                    if (isPrimary && e.target.checked) {
                        alert('Anda tidak dapat menghapus gambar utama. Silakan pilih gambar lain sebagai utama terlebih dahulu.');
                        e.target.checked = false;
                    }
                },
            }">
                <label class="form-label mb-1.5 block">Gambar Publikasi <span class="text-danger">*</span></label>

                <p class="mb-2 text-[12.5px] font-semibold text-slate-600 dark:text-slate-300">Gambar saat ini:</p>
                <div class="mb-3 flex flex-wrap gap-3">
                    @foreach($publikasi->images as $image)
                        <div class="relative">
                            <img data-blob-src="{{ route('media.blob', ['romadan_gambar_web', $image->image_path]) }}"
                                 alt="" class="img-thumbnail h-[150px] w-[150px] object-cover" style="background:#eef1f4;">

                            <div class="absolute top-0 start-0 flex m-1">
                                @if($image->is_primary)
                                    <span class="badge bg-primary">Utama</span>
                                @else
                                    <label class="flex cursor-pointer items-center gap-1 rounded bg-black/50 px-1.5 py-0.5 text-xs font-medium text-white">
                                        <input type="radio" name="primary_image" class="form-check-input" value="{{ $image->id }}">
                                        Set Utama
                                    </label>
                                @endif
                            </div>

                            <div class="absolute bottom-0 end-0 m-1">
                                <label class="flex cursor-pointer items-center gap-1 rounded bg-black/50 px-1.5 py-0.5 text-xs font-medium text-white">
                                    <input type="checkbox" name="delete_images[]" class="form-check-input" value="{{ $image->id }}"
                                           @change="guardPrimaryDelete($event, {{ $image->is_primary ? 'true' : 'false' }})">
                                    Hapus
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($publikasi->images->count() < 1)
                    <div class="alert alert-warning">Tidak ada gambar tersedia. Silakan unggah minimal satu gambar.</div>
                @endif

                <p class="mb-2 mt-3 text-[12.5px] font-semibold text-slate-600 dark:text-slate-300">Tambah gambar baru:</p>
                <input type="file" name="new_images[]" multiple accept="image/jpeg,image/png,image/jpg,image/svg"
                       @change="onNewImages($event)"
                       class="form-control {{ $errors->has('new_images') ? 'is-invalid' : '' }}">
                <span class="form-text">Anda dapat memilih beberapa gambar sekaligus.</span>

                <div class="flex flex-wrap gap-2 mt-2">
                    <template x-for="url in newPreviews" :key="url">
                        <img :src="url" class="img-thumbnail h-[150px] w-[150px] object-cover">
                    </template>
                </div>

                @error('new_images')<span class="invalid-feedback">{{ $message }}</span>@enderror
                @error('new_images.*')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <x-form.field type="file" name="file" label="File Publikasi" accept=".pdf" />
            @if($publikasi->file)
                <div class="mb-4 -mt-3 text-sm">
                    <a target="_blank" href="{{ asset('storage/romadan_file_web/'.$publikasi->file) }}">{{ $publikasi->file }}</a>
                </div>
            @endif

            <x-form.textarea name="isi" label="Isi Publikasi" required maxlength="25000" placeholder="Isi publikasi" rich id="ckeditor_classic_empty" :value="$publikasi->isi" />

            <x-form.field
                type="url"
                name="embedded_media"
                label="URL Media (Video/Image)"
                :value="$publikasi->embedded_media"
                placeholder="https://youtube.com/watch?v=example or image URL"
                help="Masukkan URL YouTube, Vimeo, atau gambar yang ingin ditampilkan"
            />
            @if($publikasi->embedded_media)
                <div class="mb-4 -mt-2">
                    <p class="mb-1 text-[12.5px] font-semibold text-slate-600 dark:text-slate-300">Preview Media:</p>
                    <div class="mb-1">{!! $publikasi->getEmbeddedMediaHtml() !!}</div>
                    <p class="text-xs text-slate-500 dark:text-slate-400">URL saat ini: {{ $publikasi->embedded_media }}</p>
                </div>
            @endif

            <div x-data="{ status: @js($currentStatus) }">
                <x-form.select name="status" label="Status Publikasi" :options="$statusOptions" :value="$currentStatus" placeholder="-- Pilih Status --" required x-model="status" />

                <div x-show="status === 'scheduled'" x-cloak>
                    <x-form.field
                        type="datetime-local"
                        name="published_at"
                        label="Waktu Terbit Terjadwal"
                        :value="old('published_at', $publikasi->published_at ? \Carbon\Carbon::parse($publikasi->published_at)->format('Y-m-d\TH:i') : '')"
                        help='Publikasi akan otomatis berstatus "published" saat waktu ini tercapai.'
                    />
                </div>
            </div>

            <x-form.field
                type="datetime-local"
                name="created_at"
                label="Tanggal Terbit"
                required
                :value="old('created_at', $publikasi->created_at ? \Carbon\Carbon::parse($publikasi->created_at)->format('Y-m-d\TH:i') : '')"
                :max="now()->format('Y-m-d\TH:i')"
            />
        </div>

        <x-form.actions :back-route="route('publikasi.index')" back-label="Batal" submit-label="Update" />
    </form>
</div>
@endsection
