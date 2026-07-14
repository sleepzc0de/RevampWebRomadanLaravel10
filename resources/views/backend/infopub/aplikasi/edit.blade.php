@extends('layouts.webromadan_backend.master_layout')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Edit Aplikasi</h5>
    </div>

    @include('layouts.webromadan_backend.session_notif')

    <form action="{{ route('aplikasi.update', encrypt($data->id)) }}" method="post" enctype="multipart/form-data" autocomplete="off">
        @csrf
        @method('PUT')
        <div class="card-body">
            <x-form.field name="judul_aplikasi" label="Judul Aplikasi" :value="$data->judul_aplikasi" required maxlength="100" />

            <x-form.field name="sub_judul_aplikasi" label="Sub Judul Aplikasi" :value="$data->sub_judul_aplikasi" required maxlength="100" />

            <x-form.field type="url" name="link_aplikasi" label="Link Aplikasi" :value="$data->link_aplikasi" required maxlength="1000" />

            <x-form.file name="image" label="Gambar Aplikasi" accept="image/jpeg,image/png,image/jpg" />
            <div class="mb-4 -mt-3">
                <img data-blob-src="{{ route('media.blob', ['romadan_gambar_web', $data->image]) }}" alt="" class="img-thumbnail" style="width: 300px; background:#eef1f4;">
            </div>
        </div>

        <x-form.actions :back-route="route('aplikasi.index')" submit-label="Update" />
    </form>
</div>
@endsection
