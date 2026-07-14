@extends('layouts.webromadan_backend.master_layout')

@section('content')
<div class="card mb-3">
    <div class="card-header">
        <h5 class="mb-0">{{ $data->judul }}</h5>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Galeri Gambar</h5>
    </div>
    <div class="card-body">
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">
            @foreach($data->images as $image)
                <div class="card mb-0">
                    <img data-blob-src="{{ route('media.blob', ['romadan_gambar_web', $image->image_path]) }}"
                         class="card-img-top h-[200px] object-cover" alt="Image" style="background:#eef1f4;">
                    <div class="card-body p-2 text-center">
                        @if($image->is_primary)
                            <span class="badge bg-primary">Gambar Utama</span>
                        @endif
                    </div>
                </div>
            @endforeach

            @if($data->images->count() < 1)
                <div class="col-span-full">
                    <div class="alert alert-warning">Tidak ada gambar tersedia.</div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
