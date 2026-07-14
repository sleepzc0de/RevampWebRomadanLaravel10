@extends('layouts.webromadan_backend.master_layout')

@php
    $kategoriOptions = collect($kategori)->mapWithKeys(fn ($item, $i) => [$item->id_kategori => ($i + 1).' - '.$item->nama_kategori])->all();
@endphp

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Tambah FAQ</h5>
    </div>

    @include('layouts.webromadan_backend.session_notif')

    <form action="{{ route('faq.store') }}" method="post" autocomplete="off">
        @csrf
        <div class="card-body">
            <x-form.field name="faq_judul" label="Pertanyaan" required maxlength="255" placeholder="Masukkan Pertanyaan FAQ" />

            <x-form.textarea name="faq_isi" label="Jawaban" required maxlength="1000" placeholder="isi Jawaban FAQ" rich />

            <x-form.select name="kategori" label="Kategori Berita" :options="$kategoriOptions" placeholder="-- Pilih Kategori --" required />
        </div>

        <x-form.actions :back-route="route('faq.index')" submit-label="Simpan" />
    </form>
</div>
@endsection
