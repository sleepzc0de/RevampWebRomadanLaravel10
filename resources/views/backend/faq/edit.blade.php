@extends('layouts.webromadan_backend.master_layout')

@php
    $kategoriOptions = collect($kategori)->mapWithKeys(fn ($item, $i) => [$item->id_kategori => ($i + 1).' - '.$item->nama_kategori])->all();
@endphp

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Edit FAQ</h5>
    </div>

    @include('layouts.webromadan_backend.session_notif')

    <form action="{{ route('faq.update', encrypt($faq->id)) }}" method="post" autocomplete="off">
        @csrf
        @method('PUT')
        <div class="card-body">
            <x-form.field name="faq_judul" label="Pertanyaan" :value="$faq->faq_judul" required maxlength="255" placeholder="Masukkan Pertanyaan FAQ" />

            <x-form.textarea name="faq_isi" label="Jawaban" :value="$faq->faq_isi" required maxlength="1000" placeholder="Jawaban FAQ" rich />

            <x-form.select name="kategori" label="Kategori FAQ" :options="$kategoriOptions" :value="$faq->kategori" placeholder="-- Pilih Kategori --" required />
        </div>

        <x-form.actions :back-route="route('faq.index')" back-label="Batal" submit-label="Update" />
    </form>
</div>
@endsection
