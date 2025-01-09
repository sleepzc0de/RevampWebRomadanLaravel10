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

    .search-loading {
    position: absolute;
    right: 3.5rem;
    top: 50%;
    transform: translateY(-50%);
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.2s ease, visibility 0.2s ease;
}

.search-loading.active {
    opacity: 1;
    visibility: visible;
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
                    <div class="publikasi-home">warta</div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="wrap-pic-welcome size2 bo-rad-10 hov-img-zoom m-l-r-auto">
                    <div class="publikasi-home-sub">The latest industry news, interviews, technologies, and resources.</div>
                </div>

                <form id="searchForm" action="{{ route('warta-kategori-fe', ['kategori' => request()->segment(2)]) }}" method="POST">
                    @csrf
                    <input type="hidden" name="current_kategori" value="{{ request()->segment(2) }}">
                    <div class="wrap-inputname size12 bo2 bo-rad-10 m-t-3 m-b-23">
                        <input
                            class="bo-rad-10 sizefull txt10 p-l-20"
                            type="text"
                            name="cari_warta"
                            placeholder="Cari warta"
                            value="{{ e($searchValue ?? '') }}"
                            maxlength="255"
                            pattern="[A-Za-z0-9\s]+"
                        >
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
                                $selectedCategory = strtolower($item->nama_kategori);
                                $isActive = $currentURL === route('warta-kategori-fe', $selectedCategory);
                            @endphp

                            <a class="btn pilihan-kategori-menu {{ $isActive ? 'active' : '' }}"
                                href="{{ route('warta-kategori-fe', $selectedCategory) }}">
                                {{ strlen($item->nama_kategori) <= 3 ? strtoupper($item->nama_kategori) : ucfirst(strtolower($item->nama_kategori)) }}
                            </a>
                        @endforeach
                    </div>
                </form>
            </div>

            <div class="col-lg-12 mt-5" id="warta-container">
                @include('frontend.publikasi.partials.warta-list', [
                    'warta' => $warta,
                    'isSearch' => $isSearch,
                    'searchValue' => $searchValue,
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
    const wartaContainer = document.getElementById('warta-container');
    let typingTimer;

    if (searchForm && searchInput) {
        console.log('Search form initialized');

        searchInput.addEventListener('input', function() {
            clearTimeout(typingTimer);
            if (this.value.length >= 1) {
                loadingSpinner.classList.add('active');
                typingTimer = setTimeout(performSearch, 500);
            }
        });

        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            loadingSpinner.classList.add('active');
            performSearch();
        });

        function performSearch() {
            // Get the original category from the URL path
            const pathSegments = window.location.pathname.split('/');
            const currentCategory = pathSegments[pathSegments.length - 1];

            const formData = new FormData();
            formData.append('cari_warta', searchInput.value);
            formData.append('current_kategori', currentCategory);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

            // Use the correct URL format
            const baseUrl = `${window.location.origin}/publikasi/warta/kategori/${currentCategory}`;

            console.log('Sending search request:');
            console.log('- Search term:', searchInput.value);
            console.log('- Category:', currentCategory);
            console.log('- URL:', baseUrl);

            fetch(baseUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html, application/xhtml+xml',
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams(formData)
            })
            .then(async response => {
                console.log('Response status:', response.status);

                if (!response.ok) {
                    const errorText = await response.text();
                    console.error('Error response:', errorText);
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.text();
            })
            .then(html => {
                console.log('Received HTML response length:', html.length);

                // Update the container
                wartaContainer.innerHTML = html;

                // Update URL while maintaining category
                const url = new URL(window.location);
                url.searchParams.set('cari_warta', searchInput.value);
                window.history.pushState({}, '', url);
            })
            .catch(error => {
                console.error('Search error:', error);
                wartaContainer.innerHTML = `
                    <div class="alert alert-danger">
                        Terjadi kesalahan saat mencari data. Silakan coba lagi.
                    </div>
                `;
            })
            .finally(() => {
                loadingSpinner.classList.remove('active');
            });
        }
    }
});
</script>
@endsection
@endsection
