@extends('layouts.webromadan_frontend.fe_master')

@section('title', $data->judul . ' — Biro Manajemen BMN dan Pengadaan')
@section('meta_description', Str::limit(strip_tags($data->isi), 155))
@section('og_type', 'article')
@if ($data->image)
@section('meta_image', asset('storage/romadan_gambar_web/' . $data->image))
@endif
@include('frontend.publikasi._article-schema')

@section('content')
@include('frontend.publikasi._detail-tipe', [
    'data' => $data,
    'tb' => $tb,
    'backRoute' => 'publikasi-index-berita-fe',
    'tipeLabel' => 'Berita',
])
@endsection
