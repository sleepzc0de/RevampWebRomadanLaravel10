@extends('layouts.webromadan_frontend.fe_master')

@section('title', 'Warta — Biro Manajemen BMN dan Pengadaan')

@section('content')
@include('frontend.publikasi._index-tipe', [
    'items' => $warta,
    'isSearch' => $isSearch,
    'searchValue' => $searchValue,
    'kategori' => $kategori,
    'tipeLabel' => 'Warta',
    'tipeDesc' => 'Warta dan informasi terkini dari kami.',
    'indexRoute' => 'publikasi-index-warta-fe',
    'kategoriRoute' => 'warta-kategori-fe',
    'searchParam' => 'cari_warta',
    'listPartial' => 'frontend.publikasi.partials.warta-list',
])
@endsection
