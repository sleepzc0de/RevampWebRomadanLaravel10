@extends('layouts.webromadan_frontend.fe_master')

@section('css_fe')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">

<style>
    .custom-isi {
        text-align: justify;
        word-wrap: break-word;
        overflow-wrap: break-word;
        line-height: 1.8;
        font-size: 1.1rem;
    }

    /* Penting: Menambahkan style untuk paragraf */
    .custom-isi p {
        margin-bottom: 1rem;
        text-align: justify;
    }

    /* Untuk layar kecil (ponsel) */
    @media (max-width: 768px) {
        .custom-isi {
            font-size: 1rem;
            line-height: 1.6;
            padding: 10px;
        }
    }

    /* Untuk layar sangat kecil (HP kecil) */
    @media (max-width: 480px) {
        .custom-isi {
            font-size: 0.95rem;
            line-height: 1.5;
            padding: 5px;
        }
    }

    .custom-judul {
        font-weight: bold;
        text-align: center;
        word-wrap: break-word;
        overflow-wrap: break-word;
        font-size: 1.4rem;
        line-height: 1.6;
        max-width: 90%;
        margin: 0 auto;
    }

    /* Untuk layar tablet */
    @media (max-width: 768px) {
        .custom-judul {
            font-size: 1.2rem;
            line-height: 1.4;
        }
    }

    /* Untuk layar ponsel kecil */
    @media (max-width: 480px) {
        .custom-judul {
            font-size: 1rem;
            line-height: 1.3;
        }
    }

    /* Image slider styles */
    .image-slider-container {
        position: relative;
        margin-bottom: 20px;
    }

    .main-slider {
        width: 100%;
        border-radius: 10px;
        overflow: hidden;
    }

    .main-slider .swiper-slide {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 400px; /* Adjust this height as needed */
    }

    .main-slider .swiper-slide img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        cursor: pointer;
    }

    .thumbnail-slider {
        margin-top: 10px;
        height: 80px;
    }

    .thumbnail-slider .swiper-slide {
        opacity: 0.4;
        width: 80px;
        height: 60px;
        cursor: pointer;
        border-radius: 5px;
        overflow: hidden;
    }

    .thumbnail-slider .swiper-slide img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .thumbnail-slider .swiper-slide-thumb-active {
        opacity: 1;
        border: 2px solid #3498db;
    }

    /* Swiper controls */
    .swiper-button-next, .swiper-button-prev {
        color: #fff;
        background: rgba(0,0,0,0.5);
        width: 40px;
        height: 40px;
        border-radius: 50%;
        --swiper-navigation-size: 20px;
    }

    .swiper-button-next:after, .swiper-button-prev:after {
        font-size: 20px;
    }

    .swiper-pagination-bullet-active {
        background: #3498db;
    }

    /* For mobile devices */
    @media (max-width: 768px) {
        .main-slider .swiper-slide {
            height: 250px;
        }

        .thumbnail-slider .swiper-slide {
            width: 60px;
            height: 45px;
        }

        .swiper-button-next, .swiper-button-prev {
            width: 30px;
            height: 30px;
            --swiper-navigation-size: 15px;
        }
    }
</style>
@endsection

@section('content')
<section class="section-welcome p-t-120 p-b-105" style="background-color: white;">
    <div class="container">
        <div class="row p-t-10">
            <div class="col-md-12">
                <div class="wrap-text-welcome">
                    <div class="t-center m-b-22 pt-3" style="text-align: left;">
                        <div class="text-center">{{$tb}}</div>
                    </div>

                    @php
                        $words = explode(' ', strip_tags($data->judul)); // Menghapus tag HTML & mengubah menjadi array kata
                        $words = array_slice($words, 0, 20); // Ambil hanya 20 kata pertama
                        $formattedTitle = implode(' ', $words); // Gabungkan kembali ke string
                        $formattedTitle = preg_replace('/((\S+\s+){10})/u', "$1<br>", $formattedTitle); // Tambahkan <br> tiap 10 kata
                    @endphp

                    <div class="txt-judul-berita-terkini-detail text-center custom-judul" title="{{ $data->judul }}">
                        {!! nl2br(e($formattedTitle)) !!}
                    </div>

                    {{-- Tambahkan jumlah views di sini --}}
                    <div class="t-center m-b-22" style="text-align: center;">
                        <span class="txt-judul-kegiatan-detail-tanggal">
                            <i class="fa-regular fa-eye"></i> {{$data->views ?? 0}} Views
                        </span>
                    </div>

                    <!-- Image Slider Section -->
                    <div class="t-center m-b-22" style="text-align: justify;">
                        <div class="txt-judul-kegiatan-detail-tanggal">
                            @if($data->images && $data->images->count() > 0)
                                <div class="mt-3 image-slider-container">
                                    <!-- Main Slider -->
                                    <div class="swiper main-slider">
                                        <div class="swiper-wrapper">
                                            @foreach($data->images as $image)
                                                <div class="swiper-slide">
                                                    <a href="{{asset('storage/romadan_gambar_web/'.$image->image_path)}}"
                                                    data-lightbox="publikasi-gallery">
                                                        <img src="{{asset('storage/romadan_gambar_web/'.$image->image_path)}}"
                                                        alt="Gambar Publikasi" class="img-fluid bo-rad-10">
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                        <!-- Add Navigation -->
                                        <div class="swiper-button-next"></div>
                                        <div class="swiper-button-prev"></div>
                                        <div class="swiper-pagination"></div>
                                    </div>

                                    <!-- Thumbnails -->
                                    @if($data->images->count() > 1)
                                    <div class="swiper-thumbnails mt-2">
                                        <div class="swiper thumbnail-slider">
                                            <div class="swiper-wrapper">
                                                @foreach($data->images as $image)
                                                    <div class="swiper-slide">
                                                        <img src="{{asset('storage/romadan_gambar_web/'.$image->image_path)}}"
                                                        alt="Thumbnail" class="img-fluid bo-rad-5">
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            @else
                                <div class="mt-3 pic-blo4 hov-img-zoom bo-rad-10 pos-relative w-100">
                                    <a href="{{asset('storage/romadan_gambar_web/'.$data->image)}}">
                                        <img src="{{asset('storage/romadan_gambar_web/'.$data->image)}}" alt="IMG-BLOG">
                                    </a>
                                </div>
                            @endif

                            <div class="txt-judul-kegiatan-detail t-center m-b-35 mt-5 px-4 custom-isi">
                                {!! $data->isi !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('script_fe')
<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize lightbox
        lightbox.option({
            'resizeDuration': 200,
            'wrapAround': true,
            'disableScrolling': true
        });

        // Check if slider elements exist before initializing
        if (document.querySelector('.thumbnail-slider')) {
            // Initialize thumbnail slider
            const thumbsSlider = new Swiper('.thumbnail-slider', {
                spaceBetween: 10,
                slidesPerView: 'auto',
                freeMode: true,
                watchSlidesProgress: true,
            });

            // Initialize main slider with thumbs
            const mainSlider = new Swiper('.main-slider', {
                spaceBetween: 10,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                thumbs: {
                    swiper: thumbsSlider,
                },
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                loop: true,
                grabCursor: true,
            });
        } else if (document.querySelector('.main-slider')) {
            // Initialize main slider without thumbs
            const mainSlider = new Swiper('.main-slider', {
                spaceBetween: 10,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                loop: true,
                grabCursor: true,
            });
        }
    });
</script>
@endsection
