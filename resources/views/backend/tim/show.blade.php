@extends('layouts.webromadan_backend.master_layout')

@section('content')
<div class="max-w-2xl">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Detail Pengembang</h5>
        </div>
        <div class="card-body space-y-4">
            <div>
                <p class="mb-1.5 text-[12.5px] font-semibold text-slate-600 dark:text-slate-300">Foto</p>
                @if($developer->photo)
                    <img data-blob-src="{{ route('media.blob', ['photos', basename($developer->photo)]) }}" alt="Developer Photo" class="img-thumbnail" style="max-width: 200px; background:#eef1f4;">
                @else
                    <span class="text-muted">Tidak ada foto</span>
                @endif
            </div>

            <div>
                <p class="mb-1.5 text-[12.5px] font-semibold text-slate-600 dark:text-slate-300">Nama</p>
                <p class="mb-0">{{ $developer->name }}</p>
            </div>

            <div>
                <p class="mb-1.5 text-[12.5px] font-semibold text-slate-600 dark:text-slate-300">Keahlian</p>
                <p class="mb-0">{{ str_replace('|', ' | ', $developer->skill) }}</p>
            </div>
        </div>
        <div class="card-footer flex justify-end gap-2">
            <a href="{{ route('pengembang.edit', $encryptedId) }}" class="btn btn-warning">Edit</a>
            <a href="{{ route('pengembang.index') }}" class="btn btn-link">Kembali</a>
        </div>
    </div>
</div>
@endsection
