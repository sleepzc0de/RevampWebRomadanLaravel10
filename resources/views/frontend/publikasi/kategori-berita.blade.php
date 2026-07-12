@extends('layouts.webromadan_frontend.fe_master')

@section('title', 'Berita — Biro Manajemen BMN dan Pengadaan')

@section('content')
@include('frontend.publikasi._kategori-tipe', [
    'items' => $berita,
    'isSearch' => $isSearch,
    'searchValue' => $searchValue,
    'kategoriList' => $kategori,
    'tipeLabel' => 'Berita',
    'tipeDesc' => 'Berita terbaru seputar kegiatan dan kebijakan kami.',
    'indexRoute' => 'publikasi-index-berita-fe',
    'kategoriRoute' => 'berita-kategori-fe',
    'searchParam' => 'cari_berita',
    'listPartial' => 'frontend.publikasi.partials.berita-list',
])
@endsection
