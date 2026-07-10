@extends('layouts.webromadan_frontend.fe_master')

@section('css_fe')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />
<style>
    .struktur-section {
        padding: 2rem 0;
    }

    .swiper {
        width: 100%;
        height: 100%;
        margin-left: auto;
        margin-right: auto;
    }

    .swiper-slide {
        text-align: center;
        background: #fff;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .swiper-slide img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 10px;
    }

    .swiper-pagination {
        position: relative;
        margin-top: 15px;
    }

    .youtube-container {
        position: relative;
        width: 100%;
        padding-bottom: 56.25%;
        height: 0;
        overflow: hidden;
        border-radius: 10px;
    }

    .youtube-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border-radius: 10px;
    }

    .media-container {
        margin-bottom: 2rem;
    }

    .struktur-content {
        text-align: justify;
        margin-bottom: 2rem;
    }

    .struktur-title {
        margin-bottom: 1.5rem;
        font-weight: 700;
        color: #333;
    }

    /* Enhanced CKEditor Content Styles */
    .ck-content {
        color: #333;
        font-family: inherit;
        line-height: 1.6;
    }

    /* Explicitly set list styles to ensure proper display */
    .ck-content ol {
        list-style-type: decimal !important;
        padding-left: 2em;
        margin-bottom: 1em;
        display: block !important;
    }

    .ck-content ol li {
        display: list-item !important;
        list-style-type: decimal !important;
        margin-bottom: 0.5em;
    }

    .ck-content ul {
        list-style-type: disc !important;
        padding-left: 2em;
        margin-bottom: 1em;
        display: block !important;
    }

    .ck-content ul li {
        display: list-item !important;
        list-style-type: disc !important;
        margin-bottom: 0.5em;
    }

    /* Nested lists */
    .ck-content ol ol {
        list-style-type: lower-alpha !important;
    }

    .ck-content ol ol li {
        list-style-type: lower-alpha !important;
    }

    .ck-content ul ul {
        list-style-type: circle !important;
    }

    .ck-content ul ul li {
        list-style-type: circle !important;
    }

    .ck-content h1,
    .ck-content h2,
    .ck-content h3,
    .ck-content h4,
    .ck-content h5,
    .ck-content h6 {
        font-weight: 700;
        margin-top: 1em;
        margin-bottom: 0.5em;
        color: #333;
    }

    .ck-content h1 { font-size: 2em; }
    .ck-content h2 { font-size: 1.75em; }
    .ck-content h3 { font-size: 1.5em; }
    .ck-content h4 { font-size: 1.25em; }
    .ck-content h5 { font-size: 1em; }
    .ck-content h6 { font-size: 0.875em; }

    .ck-content p {
        margin-top: 0;
        margin-bottom: 1em;
        text-align: justify;
    }

    .ck-content a {
        color: #0066cc;
        text-decoration: underline;
    }

    .ck-content blockquote {
        border-left: 5px solid #ccc;
        padding-left: 1em;
        margin-left: 1em;
        font-style: italic;
    }

    .ck-content img {
        max-width: 100%;
        height: auto;
        border-radius: 4px;
    }

    .ck-content table {
        border-collapse: collapse;
        width: 100%;
        margin-bottom: 1em;
        border: 1px solid #ddd;
    }

    .ck-content th,
    .ck-content td {
        padding: 0.75em;
        border: 1px solid #ddd;
        text-align: left;
    }

    .ck-content th {
        background-color: #f5f5f5;
        font-weight: bold;
    }

    .ck-content .text-tiny { font-size: 0.7em; }
    .ck-content .text-small { font-size: 0.85em; }
    .ck-content .text-big { font-size: 1.15em; }
    .ck-content .text-huge { font-size: 1.3em; }

    .ck-content .marker-yellow { background-color: #fffd7b; }
    .ck-content .marker-green { background-color: #aaffb6; }
    .ck-content .marker-pink { background-color: #ffafc9; }
    .ck-content .marker-blue { background-color: #a1d3ff; }

    .ck-content .pen-red { color: #e71313; }
    .ck-content .pen-green { color: #00a92c; }

    /* Nested Lists */
    .ck-content ol ol,
    .ck-content ul ul,
    .ck-content ul ol,
    .ck-content ol ul {
        margin-bottom: 0;
    }

    /* Fix for custom spacing between list items */
    .txt-sejarah ol li,
    .txt-sejarah ul li {
        margin-bottom: 0.5em !important;
    }

    @media (max-width: 768px) {
        .media-container, .struktur-content {
            margin-bottom: 1.5rem;
        }

        .struktur-section .row {
            flex-direction: column-reverse;
        }

        .struktur-section .row.standard-layout,
        .struktur-section .row.wide-layout,
        .struktur-section .row.compact-layout {
            display: flex;
            flex-direction: column-reverse;
        }

        .struktur-section .col-md-6,
        .struktur-section .col-md-4,
        .struktur-section .col-md-8 {
            width: 100%;
            margin-bottom: 1.5rem;
        }
    }
</style>
<style>
    /*
 * ckeditor-content.css - CSS untuk menampilkan konten yang dihasilkan CKEditor
 * Letakkan file ini di folder public/webromadan/fe/css/
 */

/* Wrappers dan kontainer */
.ck-content {
    color: #333;
    font-family: inherit;
    line-height: 1.6;
}

/* Headings */
.ck-content h1,
.ck-content h2,
.ck-content h3,
.ck-content h4,
.ck-content h5,
.ck-content h6 {
    font-weight: 700;
    margin-top: 1em;
    margin-bottom: 0.5em;
    line-height: 1.3;
    color: #333;
}

.ck-content h1 { font-size: 2em; }
.ck-content h2 { font-size: 1.75em; }
.ck-content h3 { font-size: 1.5em; }
.ck-content h4 { font-size: 1.25em; }
.ck-content h5 { font-size: 1em; }
.ck-content h6 { font-size: 0.875em; }

/* Paragraf */
.ck-content p {
    margin-top: 0;
    margin-bottom: 1em;
}

/* Lists */
/* Lists - Enhanced to ensure proper display of numbers */
.ck-content ol {
    list-style-type: decimal !important;
    padding-left: 2em;
    margin-bottom: 1em;
    display: block !important;
}

.ck-content ol li {
    display: list-item !important;
    list-style-type: decimal !important;
    margin-bottom: 0.5em;
}

.ck-content ul {
    list-style-type: disc !important;
    padding-left: 2em;
    margin-bottom: 1em;
    display: block !important;
}

.ck-content ul li {
    display: list-item !important;
    list-style-type: disc !important;
    margin-bottom: 0.5em;
}

.ck-content ol ol,
.ck-content ul ul,
.ck-content ul ol,
.ck-content ol ul {
    margin-bottom: 0;
}

/* Links */
.ck-content a {
    color: #0066cc;
    text-decoration: underline;
}

.ck-content a:hover {
    text-decoration: none;
}

/* Blockquote */
.ck-content blockquote {
    border-left: 5px solid #ccc;
    padding-left: 1em;
    margin-left: 1em;
    font-style: italic;
}

/* Images */
.ck-content img {
    max-width: 100%;
    height: auto;
    border-radius: 4px;
}

.ck-content figure.image {
    display: table;
    margin: 1em auto;
}

.ck-content figure.image img {
    display: block;
    margin: 0 auto;
}

.ck-content figure.image figcaption {
    display: table-caption;
    caption-side: bottom;
    font-size: 0.9em;
    color: #777;
    text-align: center;
    padding-top: 0.5em;
}

/* Tables */
.ck-content table {
    border-collapse: collapse;
    width: 100%;
    margin-bottom: 1em;
    border: 1px solid #ddd;
}

.ck-content th,
.ck-content td {
    padding: 0.75em;
    border: 1px solid #ddd;
    text-align: left;
}

.ck-content th {
    background-color: #f5f5f5;
    font-weight: bold;
}

/* Tekstukuran */
.ck-content .text-tiny { font-size: 0.7em; }
.ck-content .text-small { font-size: 0.85em; }
.ck-content .text-big { font-size: 1.15em; }
.ck-content .text-huge { font-size: 1.3em; }

/* Highlighting */
.ck-content .marker-yellow { background-color: #fffd7b; }
.ck-content .marker-green { background-color: #aaffb6; }
.ck-content .marker-pink { background-color: #ffafc9; }
.ck-content .marker-blue { background-color: #a1d3ff; }

/* Text colors */
.ck-content .pen-red { color: #e71313; }
.ck-content .pen-green { color: #00a92c; }

/* Code blocks */
.ck-content pre {
    background-color: #f5f5f5;
    padding: 1em;
    border-radius: 4px;
    font-family: monospace;
    overflow: auto;
}

.ck-content code {
    background-color: #f5f5f5;
    padding: 0.2em 0.4em;
    border-radius: 3px;
    font-family: monospace;
}

/* Media responsiveness */
@media (max-width: 768px) {
    .ck-content table {
        display: block;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .ck-content h1 { font-size: 1.75em; }
    .ck-content h2 { font-size: 1.5em; }
    .ck-content h3 { font-size: 1.25em; }
    .ck-content h4 { font-size: 1.1em; }
}
</style>
@endsection

@section('content')
<section class="section-welcome p-t-120 p-b-105" style="background-color: white;">
    <div class="container">
        <div class="title-section-ourmenu m-b-2">
            <h2 class="txt-judul-layanan m-t-2">
                Struktur Organisasi
            </h2>
        </div>

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @forelse ($organisasi as $item)
            <div class="struktur-section p-t-10">
                <div class="row {{ $item->layout_type }}-layout">
                    <div class="{{ $item->getResponsiveClassesAttribute()['content'] }} d-flex flex-column justify-content-center">
                        <div class="struktur-content">
                            <div class="txt-sejarah ck-content">{!! clean($item->struktur) !!}</div>
                        </div>
                    </div>

                    <div class="{{ $item->getResponsiveClassesAttribute()['media'] }}">
                        <div class="media-container">
                            @php
                                // Sort additional images by sort_order in PHP instead of SQL
                                $sortedImages = $item->additionalImages->sortBy('sort_order');
                                $hasAdditionalMedia = $sortedImages->count() > 0 || !empty($item->video_url);
                                $youtubeId = $item->getYoutubeIdAttribute();
                            @endphp

                            @if($hasAdditionalMedia)
                                <!-- Slider main container -->
                                <div class="swiper mySwiper-{{ $item->id }}">
                                    <div class="swiper-wrapper">
                                        <!-- Main image slide -->
                                        <div class="swiper-slide">
                                            <img src="{{ asset('storage/romadan_gambar_web/' . $item->image) }}" alt="{{ $item->judul }}" class="img-fluid">
                                        </div>

                                        <!-- Additional images slides -->
                                        @foreach($sortedImages as $image)
                                            <div class="swiper-slide">
                                                <img src="{{ asset('storage/romadan_gambar_web/' . $image->image_path) }}" alt="{{ $item->judul }}" class="img-fluid">
                                            </div>
                                        @endforeach

                                        <!-- Video slide (if exists) -->
                                        @if(!empty($youtubeId))
                                            <div class="swiper-slide">
                                                <div class="youtube-container">
                                                    <iframe
                                                        src="https://www.youtube.com/embed/{{ $youtubeId }}"
                                                        title="YouTube video player"
                                                        frameborder="0"
                                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                        allowfullscreen>
                                                    </iframe>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Pagination dots -->
                                    <div class="swiper-pagination"></div>

                                    <!-- Navigation arrows -->
                                    <div class="swiper-button-next"></div>
                                    <div class="swiper-button-prev"></div>
                                </div>
                            @else
                                <!-- Just show the main image if no additional images or videos -->
                                <a href="{{ asset('storage/romadan_gambar_web/' . $item->image) }}" target="_blank">
                                    <img src="{{ asset('storage/romadan_gambar_web/' . $item->image) }}" alt="{{ $item->judul }}" class="img-fluid rounded">
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <section class="section-welcome p-t-50 p-b-50" style="background-color: white;">
                <div class="container">
                    <div class="title-section-ourmenu m-b-22">
                        <h5 class="romadan-faq m-t-2">
                            Tidak ada Data, Harap hubungi Administrator !
                        </h5>
                    </div>
                </div>
            </section>
        @endforelse
    </div>
</section>
@endsection

@section('script_fe')
<script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    try {
        // Initialize Swipers - one for each struktur item
        @foreach($organisasi as $item)
            @php
                $sortedImages = $item->additionalImages->sortBy('sort_order');
                $hasAdditionalMedia = $sortedImages->count() > 0 || !empty($item->video_url);
            @endphp

            @if($hasAdditionalMedia)
                console.log('Initializing Swiper for item {{ $item->id }}');
                const swiper{{ $item->id }} = new Swiper(".mySwiper-{{ $item->id }}", {
                    slidesPerView: 1,
                    spaceBetween: 30,
                    loop: true,
                    pagination: {
                        el: ".mySwiper-{{ $item->id }} .swiper-pagination",
                        clickable: true,
                    },
                    navigation: {
                        nextEl: ".mySwiper-{{ $item->id }} .swiper-button-next",
                        prevEl: ".mySwiper-{{ $item->id }} .swiper-button-prev",
                    },
                    autoplay: {
                        delay: 5000,
                        disableOnInteraction: false,
                    },
                });

                // Pause autoplay when video slide is active
                swiper{{ $item->id }}.on('slideChange', function() {
                    try {
                        const activeSlide = this.slides[this.activeIndex];
                        const hasVideo = activeSlide.querySelector('iframe');

                        if (hasVideo) {
                            console.log('Video slide active - pausing autoplay');
                            this.autoplay.stop();
                        } else {
                            console.log('Image slide active - resuming autoplay');
                            this.autoplay.start();
                        }
                    } catch (error) {
                        console.error('Error in slideChange handler:', error);
                    }
                });
            @endif
        @endforeach
    } catch (error) {
        console.error('Error initializing Swiper instances:', error);
    }
});
</script>
@endsection
