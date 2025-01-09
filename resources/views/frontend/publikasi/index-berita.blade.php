@extends('layouts.webromadan_frontend.fe_master')

@section('css_fe')
    <style>
        /* Search input enhancement */
        .wrap-inputname.size12 {
            position: relative;
            max-width: 600px;
            margin: 0 auto;
        }

        .wrap-inputname.size12 input {
            width: 100%;
            padding: 1rem 3rem 1rem 1.5rem;
            background-color: #fff;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .wrap-inputname.size12 input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.1);
        }

        .wrap-inputname.size12::after {
            content: '\f002';
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            position: absolute;
            right: 1.5rem;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            pointer-events: none;
        }

        /* Category buttons */
        .pilihan-kategori-menu {
            padding: 0.75rem 1.5rem;
            margin: 0.5rem;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s ease;
            background-color: white;
            color: #475569;
        }

        .pilihan-kategori-menu:hover {
            background-color: #f8fafc;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .pilihan-kategori-menu.active {
            background-color: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }

        /* Article cards */
        .blo4 {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-radius: 12px;
            overflow: hidden;
            background: white;
            height: 100%;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .blo4:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        .pic-blo4 img {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }

        .text-blo4 {
            padding: 1.5rem;
        }

        .txt32 {
            font-size: 0.875rem;
            color: #64748b;
        }

        .berita-terkini-judul-romadan {
            display: block;
            margin-top: 0.5rem;
            font-size: 1.125rem;
            font-weight: 600;
            color: #1a365d;
            text-decoration: none;
            line-height: 1.5;
        }

        .berita-terkini-judul-romadan:hover {
            color: #3b82f6;
        }

        /* Headers */
        .publikasi-home {
            font-size: 2.5rem;
            font-weight: bold;
            text-align: center;
            margin-bottom: 1rem;
            color: #1a365d;
        }

        .publikasi-home-sub {
            text-align: center;
            color: #64748b;
            font-size: 1.1rem;
            margin-bottom: 2rem;
        }

        /* Search result message */
        .search-result-message {
            text-align: center;
            color: #64748b;
            font-size: 1.125rem;
            margin-bottom: 2rem;
            padding: 1rem;
            background-color: #f8fafc;
            border-radius: 8px;
        }
    </style>
@endsection

@section('content')
    <section class="section-welcome p-t-120 p-b-105" style="background-color: white;">
        <div class="container">
            <div class="col-lg-12">
                <div class="wrap-pic-welcome size2 bo-rad-10 hov-img-zoom m-l-r-auto">
                    <div class="publikasi-home">Berita</div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="wrap-pic-welcome size2 bo-rad-10 hov-img-zoom m-l-r-auto">
                    <div class="publikasi-home-sub">The latest industry news, interviews, technologies, and resources.</div>
                </div>

                <form class="form-outline mt-5" action="{{ route('publikasi-index-berita-fe') }}" method="POST"
                    autocomplete="off">
                    @csrf
                    <div class="wrap-inputname size12 bo2 bo-rad-10 m-t-3 m-b-23">
                        <input class="bo-rad-10 sizefull txt10 p-l-20" type="text" name="cari_berita"
                            placeholder="Cari berita" value="{{ $searchValue ?? '' }}">
                    </div>

                    <div class="col-lg-12 text-center">
                        <a class="btn pilihan-kategori-menu {{ Request::routeIs('publikasi-index-berita-fe') ? 'active' : '' }}"
                            href="{{ route('publikasi-index-berita-fe') }}">View All
                        </a>
                        @foreach ($kategori as $item)
                            <a class="btn pilihan-kategori-menu"
                                href="{{ route('berita-kategori-fe', strip_tags(strtolower($item->nama_kategori))) }}">
                                {{ strlen($item->nama_kategori) <= 3 ? strtoupper($item->nama_kategori) : ucfirst(strtolower($item->nama_kategori)) }}
                            </a>
                        @endforeach
                    </div>
                </form>
            </div>

            <div class="col-lg-12 mt-5">
                @if ($isSearch ?? false)
                    <div class="search-result-message">
                        <h3 class="m-0">Anda sedang mencari: "{{ $searchValue }}"</h3>
                    </div>
                @endif

                <div class="row">
                    @forelse ($berita as $item)
                        <div class="col-md-4 p-t-30">
                            <div class="blo4">
                                <div class="pic-blo4 hov-img-zoom bo-rad-10 pos-relative">
                                    <a href="{{ route('berita-fe', $item->slug) }}">
                                        <img src="{{ asset('storage/romadan_gambar_web/' . $item->image) }}" alt="IMG-BLOG">
                                    </a>
                                </div>
                                <div class="text-blo4">
                                    <div class="txt32 flex-w p-b-24">
                                        <span>
                                            {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('j F Y') }}
                                            <span class="m-r-6 m-l-4">|</span>
                                        </span>
                                        <span>
                                            {{ strlen($item->nama_kategori) <= 3 ? strtoupper($item->nama_kategori) : ucfirst(strtolower($item->nama_kategori)) }}
                                        </span>
                                        <span class="m-l-4">
                                            <i class="fa-regular fa-eye"></i> {{ $item->views }} Views
                                        </span>
                                    </div>
                                    <a href="{{ route('berita-fe', $item->slug) }}"
                                        class="berita-terkini-judul-romadan">{{ $item->judul }}</a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center">
                            <div class="title-section-ourmenu m-b-22">
                                <h5 class="romadan-faq m-t-5">
                                    Mohon maaf, data yang Bapak/Ibu cari belum tersedia :(
                                </h5>
                            </div>
                        </div>
                    @endforelse
                </div>

                <div class="d-flex justify-content-center mt-5">
                    {!! $berita->appends(request()->input())->links() !!}
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script_fe')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.querySelector('input[name="cari_berita"]');
            let typingTimer;
            const doneTypingInterval = 500;

            if (searchInput) {
                // Auto-submit search after typing stops
                searchInput.addEventListener('keyup', function(e) {
                    clearTimeout(typingTimer);
                    if (this.value) {
                        typingTimer = setTimeout(() => {
                            this.closest('form').submit();
                        }, doneTypingInterval);
                    }
                });

                // Handle enter key
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        clearTimeout(typingTimer);
                        this.closest('form').submit();
                    }
                });
            }
        });
    </script>
@endsection
