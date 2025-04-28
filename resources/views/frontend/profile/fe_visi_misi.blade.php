@extends('layouts.webromadan_frontend.fe_master')

@section('css_fe')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<style>
    .content-slider {
        margin-bottom: 30px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        border-radius: 10px;
        overflow: hidden;
    }

    .slick-slide {
        position: relative;
    }

    .slick-slide img {
        width: 100%;
        height: auto;
        display: block;
        border-radius: 5px;
    }

    .video-slide {
        position: relative;
        padding-bottom: 56.25%; /* 16:9 aspect ratio */
        height: 0;
        overflow: hidden;
    }

    .video-slide iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: 0;
    }

    .slick-prev, .slick-next {
        z-index: 10;
        width: 40px;
        height: 40px;
        background: rgba(0, 0, 0, 0.5);
        border-radius: 50%;
        transition: all 0.3s ease;
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
        bottom: 10px;
    }

    .slick-dots li button:before {
        color: #fff;
        opacity: 0.8;
        font-size: 12px;
    }

    .slick-dots li.slick-active button:before {
        color: #fff;
        opacity: 1;
    }

    .content-container {
        padding: 30px;
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        margin-bottom: 30px;
    }

    .section-title {
        font-size: 1.8rem;
        font-weight: bold;
        margin-bottom: 20px;
        color: #333;
        position: relative;
        padding-bottom: 10px;
        text-align: center;
    }

    .section-title:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 3px;
        background-color: #007bff;
    }

    .content-section {
        margin-bottom: 40px;
    }

    .content-section h3 {
        font-size: 1.5rem;
        margin-bottom: 15px;
        color: #444;
        border-left: 4px solid #007bff;
        padding-left: 15px;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .section-title {
            font-size: 1.5rem;
        }

        .content-section h3 {
            font-size: 1.3rem;
        }

        .content-container {
            padding: 20px;
        }

        .slick-prev, .slick-next {
            width: 30px;
            height: 30px;
        }
    }

    @media (max-width: 576px) {
        .section-title {
            font-size: 1.3rem;
        }

        .content-section h3 {
            font-size: 1.1rem;
        }

        .content-container {
            padding: 15px;
        }
    }
</style>
@endsection

@section('content')
<section class="section-welcome p-t-80 p-b-50" style="background-color: #f8f9fa;">
    <div class="container">
        @forelse ($visimisi as $item)
            <div class="title-section-ourmenu text-center mb-4">
                <h3 class="section-title mx-auto">{{ $item->judul }}</h3>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <!-- Combined Slider for Images and Video -->
                    <div class="content-slider">
                        <!-- Main image first -->
                        <div>
                            <img src="{{ asset('storage/romadan_gambar_web/' . $item->image) }}"
                                 alt="Visi Misi {{ $item->judul }}"
                                 class="img-fluid">
                        </div>

                        <!-- Additional images -->
                        @if($item->images && $item->images->count() > 0)
                            @foreach($item->images->sortBy('sort_order') as $image)
                                <div>
                                    <img src="{{ asset('storage/romadan_gambar_web/' . $image->image) }}"
                                         alt="Visi Misi {{ $item->judul }}"
                                         class="img-fluid">
                                </div>
                            @endforeach
                        @endif

                        <!-- Video if available -->
                        @if(!empty($item->video_url))
                            @php
                                $videoId = null;
                                $videoType = null;

                                // Parse YouTube URL
                                if (strpos($item->video_url, 'youtube.com') !== false || strpos($item->video_url, 'youtu.be') !== false) {
                                    $videoType = 'youtube';
                                    preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $item->video_url, $matches);
                                    $videoId = isset($matches[1]) ? $matches[1] : null;
                                }
                                // Parse Vimeo URL
                                elseif (strpos($item->video_url, 'vimeo.com') !== false) {
                                    $videoType = 'vimeo';
                                    preg_match('/vimeo\.com\/([0-9]+)/', $item->video_url, $matches);
                                    $videoId = isset($matches[1]) ? $matches[1] : null;
                                }
                            @endphp

                            <div class="video-slide">
                                @if($videoType == 'youtube' && $videoId)
                                    <iframe src="https://www.youtube.com/embed/{{ $videoId }}?rel=0"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                            allowfullscreen></iframe>
                                @elseif($videoType == 'vimeo' && $videoId)
                                    <iframe src="https://player.vimeo.com/video/{{ $videoId }}"
                                            allow="autoplay; fullscreen; picture-in-picture"
                                            allowfullscreen></iframe>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <!-- Content container -->
                <div class="col-md-12">
                    <div class="content-container">
                        <!-- Visi section -->
                        <div class="content-section">
                            <h3>Visi</h3>
                            <div class="visi-content">
                                {!! $item->visi !!}
                            </div>
                        </div>

                        <!-- Misi section -->
                        <div class="content-section">
                            <h3>Misi</h3>
                            <div class="misi-content">
                                {!! $item->misi !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <section class="py-5 text-center">
                <div class="container">
                    <div class="alert alert-warning">
                        <h5 class="mb-0">Tidak ada Data, Harap hubungi Administrator!</h5>
                    </div>
                </div>
            </section>
        @endforelse
    </div>
</section>
@endsection

@section('script_fe')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
<script>
    $(document).ready(function(){
        $('.content-slider').slick({
            dots: true,
            infinite: true,
            speed: 700,
            fade: false,
            cssEase: 'ease-in-out',
            autoplay: true,
            autoplaySpeed: 5000,
            slidesToShow: 1,
            slidesToScroll: 1,
            adaptiveHeight: true,
            prevArrow: '<button type="button" class="slick-prev"><i class="fas fa-chevron-left"></i></button>',
            nextArrow: '<button type="button" class="slick-next"><i class="fas fa-chevron-right"></i></button>',
            responsive: [
                {
                    breakpoint: 768,
                    settings: {
                        arrows: false
                    }
                }
            ]
        });

        // Pause autoplay when video is in view
        $('.content-slider').on('beforeChange', function(event, slick, currentSlide, nextSlide){
            let slideType = $(slick.$slides[currentSlide]).find('iframe').length > 0 ? 'video' : 'image';
            if(slideType === 'video') {
                // Pause any playing videos when changing slides
                $(slick.$slides[currentSlide]).find('iframe').each(function(){
                    let src = $(this).attr('src');
                    $(this).attr('src', src);
                });
            }
        });
    });
</script>
@endsection
