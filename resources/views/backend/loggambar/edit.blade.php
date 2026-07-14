@extends('layouts.webromadan_backend.master_layout')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Edit Gambar</h5>
    </div>

    @include('layouts.webromadan_backend.session_notif')

    <form action="{{ route('loggambar.update', encrypt($loggambars->id)) }}" method="post" enctype="multipart/form-data" autocomplete="off">
        @csrf
        @method('PUT')
        <div class="card-body">
            <x-form.field name="nama_gambar" label="Nama Gambar" :value="$loggambars->nama_gambar" required maxlength="255" placeholder="Masukkan Nama Gambar" />

            <x-form.file name="image" label="Gambar" accept="image/jpeg,image/png,image/jpg" />
            <div class="mb-4 -mt-3">
                <p class="mb-1 text-xs text-slate-500 dark:text-slate-400">Gambar saat ini:</p>
                <img data-blob-src="{{ route('media.blob', ['romadan_gambar_web', $loggambars->image]) }}" alt="" class="img-thumbnail" style="width: 300px; background:#eef1f4;">
            </div>
        </div>

        <x-form.actions :back-route="route('loggambar.index')" back-label="Batal" submit-label="Update" />
    </form>
</div>
@endsection
