@extends('layouts.webromadan_frontend.fe_master')

@section('title', $data->judul . ' — Biro Manajemen BMN dan Pengadaan')
@section('meta_description', Str::limit(strip_tags($data->isi), 155))

@section('content')
@include('frontend.publikasi._detail-tipe', [
    'data' => $data,
    'tb' => $tb,
    'backRoute' => 'publikasi-index-warta-fe',
    'tipeLabel' => 'Warta',
])
@endsection
