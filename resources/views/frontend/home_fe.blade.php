@extends('layouts.webromadan_frontend.fe_master')

@section('title', 'Beranda — Biro Manajemen BMN dan Pengadaan')

@section('content')
    @include('frontend.home.fe_slider')
    @include('frontend.home.fe_welcome')
    @include('frontend.home.fe_berita')
    @include('frontend.home.fe_kumpulperaturan')
@endsection
