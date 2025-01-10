{{-- kategori-warta.blade.php --}}

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

        .warta-terkini-judul-romadan {
            display: block;
            margin-top: 0.5rem;
            font-size: 1.125rem;
            font-weight: 600;
            color: #1a365d;
            text-decoration: none;
            line-height: 1.5;
        }

        .warta-terkini-judul-romadan:hover {
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

        .search-result-message-not-found {
            text-align: center;
            color: #fff;
            font-size: 1.125rem;
            margin: 2rem auto;
            padding: 1.5rem 2rem;
            background: linear-gradient(135deg, #ff3333, #cc0000);
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(204, 0, 0, 0.2);
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            max-width: 800px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .search-result-message-not-found:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(204, 0, 0, 0.25);
        }

        .search-result-message-not-found::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(to right,
                    rgba(255, 255, 255, 0) 0%,
                    rgba(255, 255, 255, 0.1) 50%,
                    rgba(255, 255, 255, 0) 100%);
            transform: rotate(45deg);
            animation: shine 3s infinite;
        }

        .search-result-message-not-found::before {
            content: '🔍';
            margin-right: 10px;
            font-size: 1.2em;
            vertical-align: middle;
        }

        @keyframes shine {
            0% {
                left: -50%;
                opacity: 0;
            }

            50% {
                opacity: 1;
            }

            100% {
                left: 150%;
                opacity: 0;
            }
        }


        .search-loading {
            position: absolute;
            right: 3.5rem;
            top: 50%;
            transform: translateY(-50%);
            display: none;
        }

        .search-loading.active {
            display: block;
        }

        .loading-spinner {
            width: 20px;
            height: 20px;
            border: 2px solid #e2e8f0;
            border-top: 2px solid #3b82f6;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Loading state */
        .search-result-message-not-found.loading {
            background: linear-gradient(135deg, #ff4d4d, #e60000);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 4px 15px rgba(204, 0, 0, 0.2);
            }

            50% {
                box-shadow: 0 4px 25px rgba(204, 0, 0, 0.4);
            }

            100% {
                box-shadow: 0 4px 15px rgba(204, 0, 0, 0.2);
            }
        }

        @media (max-width: 768px) {
            .search-result-message-not-found {
                margin: 1.5rem 1rem;
                padding: 1.25rem 1rem;
                font-size: 1rem;
            }
        }

        /* Error state */
        .search-result-message-not-found.error {
            background: linear-gradient(135deg, #ff6666, #ff0000);
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-5px);
            }

            75% {
                transform: translateX(5px);
            }
        }


        /* Update existing search input styles */
        .wrap-inputname.size12 input {
            padding-right: 4.5rem;
            /* Increased to accommodate both icons */
        }

        /* Style for search icon */
        .search-icon {
            position: absolute;
            right: 1.5rem;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            transition: opacity 0.2s ease;
        }

        .search-icon.hidden {
            opacity: 0;
        }

        .search-result-container {
            transition: opacity 0.3s ease;
        }

        .search-result-container.loading {
            opacity: 0.5;
        }
    </style>
@endsection

@section('content')
    <section class="section-welcome p-t-120 p-b-105" style="background-color: white;">
        <div class="container">
            <div class="col-lg-12">
                <div class="wrap-pic-welcome size2 bo-rad-10 hov-img-zoom m-l-r-auto">
                    <div class="publikasi-home">Warta</div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="wrap-pic-welcome size2 bo-rad-10 hov-img-zoom m-l-r-auto">
                    <div class="publikasi-home-sub">The latest industry news, interviews, technologies, and resources.</div>
                </div>

                {{-- Improved search form with security features --}}
                <form id="searchForm"
                      data-action="{{ route('warta-kategori-fe', ['kategori' => request()->segment(2)]) }}"
                      method="POST"
                      autocomplete="off">
                    @csrf
                    <input type="hidden" name="current_kategori" value="{{ e(request()->segment(2)) }}">
                    <div class="wrap-inputname size12 bo2 bo-rad-10 m-t-3 m-b-23">
                        <input class="bo-rad-10 sizefull txt10 p-l-20"
                               type="text"
                               name="cari_warta"
                               placeholder="Cari warta"
                               value="{{ e($searchValue ?? '') }}"
                               maxlength="255"
                               pattern="[A-Za-z0-9\s]+"
                               autocomplete="off">
                        <div class="search-loading">
                            <div class="loading-spinner"></div>
                        </div>
                        <div class="search-icon">
                            <i class="fa fa-search"></i>
                        </div>
                    </div>

                    <div class="col-lg-12 text-center">
                        <a class="btn pilihan-kategori-menu {{ Request::routeIs('publikasi-index-warta-fe') ? 'active' : '' }}"
                            href="{{ route('publikasi-index-warta-fe') }}">
                            View All
                        </a>

                        @foreach ($kategori as $item)
                            @php
                                $currentURL = Request::url();
                                $selectedCategory = e(strtolower($item->nama_kategori));
                                $isActive = $currentURL === route('warta-kategori-fe', $selectedCategory);
                            @endphp

                            <a class="btn pilihan-kategori-menu {{ $isActive ? 'active' : '' }}"
                                href="{{ route('warta-kategori-fe', $selectedCategory) }}">
                                {{ strlen($item->nama_kategori) <= 3 ? strtoupper(e($item->nama_kategori)) : ucfirst(strtolower(e($item->nama_kategori))) }}
                            </a>
                        @endforeach
                    </div>
                </form>
            </div>

            <div class="col-lg-12 mt-5" id="warta-container">
                @include('frontend.publikasi.partials.warta-list', [
                    'warta' => $warta,
                    'isSearch' => $isSearch,
                    'searchValue' => e($searchValue),
                ])
            </div>
        </div>
    </section>

    @section('script_fe')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const searchForm = document.getElementById('searchForm');
                const searchInput = document.querySelector('input[name="cari_warta"]');
                const loadingSpinner = document.querySelector('.search-loading');
                const searchIcon = document.querySelector('.search-icon');
                const wartaContainer = document.getElementById('warta-container');
                let typingTimer;
                let lastSearchTime = 0;
                const minSearchInterval = 500; // Minimum time between searches in ms

                if (searchForm && searchInput) {
                    // Input validation
                    searchInput.addEventListener('input', function(e) {
                        // Allow only alphanumeric characters and spaces
                        this.value = this.value.replace(/[^A-Za-z0-9\s]/g, '');
                    });

                    searchForm.addEventListener('submit', function(e) {
                        e.preventDefault();
                        const now = Date.now();
                        if (now - lastSearchTime >= minSearchInterval) {
                            performSearch();
                            lastSearchTime = now;
                        }
                    });

                    searchInput.addEventListener('input', function() {
                        clearTimeout(typingTimer);
                        if (this.value.length >= 3) {
                            loadingSpinner.classList.add('active');
                            searchIcon.classList.add('hidden');
                            typingTimer = setTimeout(() => {
                                const now = Date.now();
                                if (now - lastSearchTime >= minSearchInterval) {
                                    performSearch();
                                    lastSearchTime = now;
                                }
                            }, 500);
                        }
                    });

                    function performSearch() {
                        // Get the original category from the URL path
                        const pathSegments = window.location.pathname.split('/');
                        const currentCategory = pathSegments[pathSegments.length - 1];

                        const formData = new FormData(searchForm);

                        // Show loading state
                        loadingSpinner.classList.add('active');
                        searchIcon.classList.add('hidden');
                        wartaContainer.style.opacity = '0.5';

                        // Use the form's action URL
                        const url = searchForm.dataset.action;

                        fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'text/html, application/xhtml+xml'
                            },
                            body: formData
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                            return response.text();
                        })
                        .then(html => {
                            wartaContainer.innerHTML = html;

                            // Update URL safely
                            const url = new URL(window.location);
                            url.searchParams.set('cari_warta', searchInput.value);
                            window.history.pushState({}, '', url);
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            wartaContainer.innerHTML = `
                                <div class="alert alert-danger">
                                    Terjadi kesalahan saat mencari data. Silakan coba lagi.
                                </div>`;
                        })
                        .finally(() => {
                            loadingSpinner.classList.remove('active');
                            searchIcon.classList.remove('hidden');
                            wartaContainer.style.opacity = '1';
                        });
                    }
                }
            });
        </script>
    @endsection
@endsection
