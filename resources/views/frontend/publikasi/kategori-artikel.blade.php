@extends('layouts.webromadan_frontend.fe_master')

@section('title', 'Artikel — Biro Manajemen BMN dan Pengadaan')

@section('content')
@include('frontend.publikasi._kategori-tipe', [
    'items' => $artikel,
    'isSearch' => $isSearch,
    'searchValue' => $searchValue,
    'kategoriList' => $kategori,
    'tipeLabel' => 'Artikel',
    'tipeDesc' => 'Artikel dan opini seputar BMN & Pengadaan.',
    'indexRoute' => 'publikasi-index-artikel-fe',
    'kategoriRoute' => 'artikel-kategori-fe',
    'searchParam' => 'cari_artikel',
    'listPartial' => 'frontend.publikasi.partials.artikel-list',
])
@endsection
