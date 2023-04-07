@extends('layouts.webromadan_frontend.fe_master')

@section('css_fe')
@endsection


@section('content')
{{-- 
    <!-- Sidebar -->
    @include('frontend.home.fe_sidebar') --}}

	<!-- Slide1 -->
    @include('frontend.home.fe_slider')

	<!-- Welcome -->
    @include('frontend.home.fe_welcome')

	<!-- Berita -->
    @include('frontend.home.fe_berita')

	<!-- Kumpulan Peraturan -->
    @include('frontend.home.fe_kumpulperaturan')

@section('script_fe')
@endsection

@endsection