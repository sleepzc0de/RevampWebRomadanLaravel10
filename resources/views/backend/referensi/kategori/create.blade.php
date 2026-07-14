@extends('layouts.webromadan_backend.master_layout')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Tambah Referensi Kategori Berita</h5>
    </div>

    @include('layouts.webromadan_backend.session_notif')

    <form action="{{ route('kategori.store') }}" method="post" autocomplete="off">
        @csrf
        <div class="card-body">
            <x-form.field
                name="nama_kategori"
                label="Nama Kategori"
                required
                maxlength="255"
                placeholder="Masukkan Nama Kategori"
            />
        </div>

        <x-form.actions :back-route="url()->previous()" submit-label="Simpan" />
    </form>
</div>
@endsection
