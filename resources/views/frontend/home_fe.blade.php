@extends('layouts.webromadan_frontend.fe_master')

@section('css_fe')
<style>
    .custom-judul {
    font-weight: bold;
    text-align: center;
    word-wrap: break-word;
    overflow-wrap: break-word;
    font-size: 1.8rem;
    line-height: 1.6;
    max-width: 90%;
    max-height: 6.5rem; /* Batas tinggi untuk mencegah judul terlalu panjang */
    overflow: hidden; /* Mencegah teks keluar */
    display: -webkit-box;
    -webkit-line-clamp: 4; /* Maksimum 4 baris */
    -webkit-box-orient: vertical;
    text-overflow: ellipsis;
    margin: 0 auto;
}

/* Untuk layar tablet */
@media (max-width: 768px) {
    .custom-judul {
        font-size: 1.5rem;
        line-height: 1.4;
        max-height: 5.5rem;
        -webkit-line-clamp: 3;
    }
}

/* Untuk layar ponsel kecil */
@media (max-width: 480px) {
    .custom-judul {
        font-size: 1.2rem;
        line-height: 1.3;
        max-height: 4.5rem;
        -webkit-line-clamp: 3;
    }
}

</style>

<style>
    @media screen and (max-width: 769px) {
    .wrap-content-slide1 {
        padding-top: 80px !important;
        padding-bottom: 80px !important;
    }

    .caption1-slide1 {
        font-size: 16px;
        margin-bottom: 15px !important;
    }

    .caption2-slide1 {
        font-size: 18px;
        margin-bottom: 25px !important;
    }

    .btn-romadan-title {
        padding: 10px 15px;
        font-size: 14px;
    }
}

@media screen and (max-width: 480px) {
    .wrap-content-slide1 {
        padding-top: 50px !important;
        padding-bottom: 50px !important;
    }

    .caption1-slide1 {
        font-size: 14px;
        margin-bottom: 10px !important;
    }

    .caption2-slide1 {
        font-size: 16px;
        margin-bottom: 15px !important;
    }

    .btn-romadan-title {
        padding: 8px 12px;
        font-size: 12px;
    }
}
</style>
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
