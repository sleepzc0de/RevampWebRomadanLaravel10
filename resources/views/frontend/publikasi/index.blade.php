@extends('layouts.webromadan_frontend.fe_master')

@section('title', 'Publikasi — Biro Manajemen BMN dan Pengadaan')

@section('content')
<section class="bg-navy-900 py-16 text-center text-white sm:py-20">
    <div class="fe-container">
        <span class="fe-eyebrow justify-center text-gold-400">Publikasi</span>
        <h1 class="mt-3 text-3xl font-extrabold sm:text-4xl">Publikasi</h1>
        <p class="mx-auto mt-3 max-w-xl text-slate-300">Berita, warta, dan artikel terbaru seputar Barang Milik Negara &amp; Pengadaan.</p>
    </div>
</section>

@include('frontend.publikasi._terkini-section', [
    'items' => $berita_terkini_publikasi,
    'label' => 'Berita',
    'eyebrow' => 'Terkini',
    'detailRoute' => 'berita-fe',
    'indexRoute' => 'publikasi-index-berita-fe',
    'bg' => 'bg-white',
])

@include('frontend.publikasi._terkini-section', [
    'items' => $warta_terkini_publikasi,
    'label' => 'Warta',
    'eyebrow' => 'Terkini',
    'detailRoute' => 'warta-fe',
    'indexRoute' => 'publikasi-index-warta-fe',
    'bg' => 'bg-slate-50',
])

@include('frontend.publikasi._terkini-section', [
    'items' => $artikel_terkini_publikasi,
    'label' => 'Artikel',
    'eyebrow' => 'Terkini',
    'detailRoute' => 'artikel-fe',
    'indexRoute' => 'publikasi-index-artikel-fe',
    'bg' => 'bg-white',
])
@endsection
