@extends('layouts.webromadan_backend.master_layout')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Tambah Informasi Publik Home</h5>
    </div>

    @include('layouts.webromadan_backend.session_notif')

    <form action="{{ route('informasi-publik.store-home') }}" method="post" autocomplete="off">
        @csrf
        <div class="card-body">
            <x-form.field name="judul" label="Judul Infopub" required maxlength="255" placeholder="Masukkan Judul Informasi Publik" />

            <x-form.textarea name="isi" label="Isi Infopub" required maxlength="3000" placeholder="isi kegiatan" rich />
        </div>

        <x-form.actions :back-route="route('informasi-publik.index')" submit-label="Simpan" />
    </form>
</div>
@endsection
