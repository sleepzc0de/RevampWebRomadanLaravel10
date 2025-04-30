@extends('layouts.webromadan_frontend.fe_master')

@section('css_fe')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
<style>
    /* Media slider styles */
    .media-slider {
        width: 100%;
        margin: 0 auto;
    }

    .media-slide {
        position: relative;
        overflow: hidden;
        border-radius: 10px;
    }

    .media-slide img {
        width: 100%;
        height: 400px;
        object-fit: cover;
    }

    .media-slide iframe {
        width: 100%;
        height: 400px;
        border: none;
    }

    .slick-prev, .slick-next {
        z-index: 10;
        background: rgba(0, 0, 0, 0.5);
        width: 40px;
        height: 40px;
        border-radius: 50%;
    }

    .slick-prev:hover, .slick-next:hover {
        background: rgba(0, 0, 0, 0.8);
    }

    .slick-prev {
        left: 15px;
    }

    .slick-next {
        right: 15px;
    }

    .slick-dots {
        bottom: 15px;
    }

    .slick-dots li button:before {
        color: white;
        font-size: 12px;
        opacity: 0.8;
    }

    .slick-dots li.slick-active button:before {
        color: white;
        opacity: 1;
    }

    /* WYSIWYG content styling with consistent fonts */
    .txt-sejarah {
        line-height: 1.8;
        text-align: justify;
    }

    .txt-sejarah p {
        margin-bottom: 1rem;
    }

    .txt-sejarah h1,
    .txt-sejarah h2,
    .txt-sejarah h3,
    .txt-sejarah h4,
    .txt-sejarah h5,
    .txt-sejarah h6 {
        margin-top: 1.5rem;
        margin-bottom: 1rem;
        font-weight: 600;
    }

    /* Fix for font consistency */
    .txt-sejarah,
    .txt-sejarah p,
    .txt-sejarah ol,
    .txt-sejarah ul,
    .txt-sejarah ol li,
    .txt-sejarah ul li {
        font-family: inherit !important;
        font-size: inherit !important;
        color: inherit !important;
        line-height: 1.8 !important;
    }

    /* Fix for numbered lists */
    .txt-sejarah ol {
        list-style-type: decimal !important;
        margin-left: 2rem !important;
        margin-bottom: 1rem !important;
        padding-left: 1rem !important;
        display: block !important;
    }

    .txt-sejarah ol li {
        display: list-item !important;
        list-style: decimal outside !important;
        margin-bottom: 0.5rem !important;
        padding-left: 0.5rem !important;
        font-weight: normal !important;
    }

    /* Fix for bullet lists */
    .txt-sejarah ul {
        list-style-type: disc !important;
        margin-left: 2rem !important;
        margin-bottom: 1rem !important;
        padding-left: 1rem !important;
        display: block !important;
    }

    .txt-sejarah ul li {
        display: list-item !important;
        list-style: disc outside !important;
        margin-bottom: 0.5rem !important;
        padding-left: 0.5rem !important;
        font-weight: normal !important;
    }

    /* Fix for nested lists */
    .txt-sejarah ol ol,
    .txt-sejarah ul ul,
    .txt-sejarah ul ol,
    .txt-sejarah ol ul {
        margin-top: 0.5rem !important;
        margin-bottom: 0.5rem !important;
    }

    .txt-sejarah ol ol li {
        list-style-type: lower-alpha !important;
    }

    .txt-sejarah ul ul li {
        list-style-type: circle !important;
    }

    /* Lightbox styling */
    .media-slide a {
        display: block;
        width: 100%;
        height: 100%;
    }

    /* Other content styling */
    .txt-sejarah img {
        max-width: 100%;
        height: auto;
        margin: 1rem 0;
        border-radius: 5px;
    }

    .txt-sejarah blockquote {
        border-left: 4px solid #ddd;
        padding: 0.5rem 1rem;
        margin: 1rem 0;
        background: #f9f9f9;
        border-radius: 0 5px 5px 0;
    }

    .txt-sejarah table {
        width: 100%;
        margin-bottom: 1rem;
        border-collapse: collapse;
    }

    .txt-sejarah table td,
    .txt-sejarah table th {
        padding: 0.5rem;
        border: 1px solid #ddd;
    }

    /* Responsive styling */
    @media (max-width: 768px) {
        .media-slide img,
        .media-slide iframe {
            height: 250px;
        }

        .section-welcome {
            padding-top: 60px;
            padding-bottom: 60px;
        }

        .slick-prev,
        .slick-next {
            width: 30px;
            height: 30px;
        }

        /* Pada mobile, teks akan berada di atas, gambar di bawah */
        .order-md-1 {
            order: 1;
        }
        .order-md-2 {
            order: 2;
        }
    }
</style>
@endsection

@section('content')
<section class="section-welcome p-t-120 p-b-105" style="background-color: white;">
    <div class="container">
        <!-- Judul utama halaman -->
        <div class="title-section-ourmenu m-b-4">
            <h5 class="txt-judul-layanan m-t-2">
                Tentang
            </h5>
        </div>

        @forelse ($tentang as $item)
            <div class="row p-t-10 align-items-center">
                <!-- Konten di sebelah kiri (pada desktop) -->
                <div class="col-md-6 order-md-1 mb-4 mb-md-0">
                    <div class="wrap-text-welcome">

                        <!-- Konten tentang -->
                        <div class="txt-sejarah">
                            {!! $item->tentang !!}
                        </div>
                    </div>
                </div>

                <!-- Media Slider di sebelah kanan (pada desktop) -->
                <div class="col-md-6 order-md-2">
                    @php
                        $hasMedia = false;
                        $mediaItems = [];

                        // Membuat array media dari gambar utama dan tambahan
                        if(!empty($item->image)) {
                            $mediaItems[] = [
                                'type' => 'image',
                                'path' => $item->image,
                                'original_name' => 'Gambar Utama'
                            ];
                            $hasMedia = true;
                        }

                        // Menambahkan gambar tambahan jika ada
                        if(isset($item->additionalImages) && $item->additionalImages->count() > 0) {
                            foreach($item->additionalImages as $additionalImage) {
                                $mediaItems[] = [
                                    'type' => 'image',
                                    'path' => $additionalImage->image_path,
                                    'original_name' => 'Gambar Tambahan'
                                ];
                            }
                            $hasMedia = true;
                        }

                        // Menambahkan video jika ada
                        if(!empty($item->video_url)) {
                            $mediaItems[] = [
                                'type' => 'video',
                                'url' => $item->video_url,
                                'original_name' => 'Video'
                            ];
                            $hasMedia = true;
                        }
                    @endphp

                    @if($hasMedia)
                        <div class="media-slider">
                            @foreach($mediaItems as $media)
                                @if($media['type'] === 'image')
                                    <div class="media-slide">
                                        <a href="{{ asset('storage/romadan_gambar_web/' . $media['path']) }}"
                                           data-lightbox="tentang-images"
                                           data-title="{{ $media['original_name'] ?? 'Gambar Tentang' }}">
                                            <img src="{{ asset('storage/romadan_gambar_web/' . $media['path']) }}"
                                                 alt="{{ $media['original_name'] ?? 'Gambar Tentang' }}">
                                        </a>
                                    </div>
                                @elseif($media['type'] === 'video')
                                    <div class="media-slide">
                                        @php
                                            $videoUrl = $media['url'];
                                            $embedUrl = $videoUrl;

                                            // Convert YouTube watch URL to embed URL
                                            if(strpos($videoUrl, 'youtube.com/watch') !== false) {
                                                $videoId = substr($videoUrl, strpos($videoUrl, 'v=') + 2);
                                                if(strpos($videoId, '&') !== false) {
                                                    $videoId = substr($videoId, 0, strpos($videoId, '&'));
                                                }
                                                $embedUrl = "https://www.youtube.com/embed/{$videoId}?autoplay=0";
                                            }
                                            // Convert YouTube short URL
                                            elseif(strpos($videoUrl, 'youtu.be/') !== false) {
                                                $videoId = substr($videoUrl, strrpos($videoUrl, '/') + 1);
                                                $embedUrl = "https://www.youtube.com/embed/{$videoId}?autoplay=0";
                                            }
                                            // Vimeo URL conversion
                                            elseif(strpos($videoUrl, 'vimeo.com/') !== false) {
                                                $vimeoId = substr($videoUrl, strrpos($videoUrl, '/') + 1);
                                                $embedUrl = "https://player.vimeo.com/video/{$vimeoId}";
                                            }
                                        @endphp
                                        <iframe src="{{ $embedUrl }}"
                                                allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                allowfullscreen></iframe>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="row">
                <div class="col-12 text-center p-5">
                    <h5 class="romadan-faq">
                        Tidak ada Data, Harap hubungi Administrator!
                    </h5>
                </div>
            </div>
        @endforelse
    </div>
</section>
@endsection

@section('script_fe')
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
<script>
    $(document).ready(function(){
        $('.media-slider').slick({
            dots: true,
            infinite: true,
            speed: 500,
            slidesToShow: 1,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 5000,
            adaptiveHeight: true,
            lazyLoad: 'ondemand',
            prevArrow: '<button type="button" class="slick-prev"><i class="fa fa-chevron-left"></i></button>',
            nextArrow: '<button type="button" class="slick-next"><i class="fa fa-chevron-right"></i></button>',
            responsive: [
                {
                    breakpoint: 768,
                    settings: {
                        arrows: false
                    }
                }
            ]
        });

        // Konfigurasi lightbox
        lightbox.option({
            'resizeDuration': 200,
            'wrapAround': true,
            'disableScrolling': true,
            'fadeDuration': 300
        });
    });
</script>
@endsection
