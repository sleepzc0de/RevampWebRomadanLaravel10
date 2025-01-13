{{-- resources/views/frontend/infopublik/aplikasi-index.blade.php --}}
@extends('layouts.webromadan_frontend.fe_master')

@section('css_fe')
    <style>
        /* Modern Design System */
        :root {
            --primary-color: #2563eb;
            --secondary-color: #1e40af;
            --background-light: #f8fafc;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Main Layout */
        .main-container {
            padding-top: 120px;
            padding-bottom: 80px;
            /* background: linear-gradient(180deg, #EDEDED 0%, #FFFFFF 100%); */
            min-height: 100vh;
        }

        /* Header Styles */

        .title-section-ourmenu {
            margin-bottom: 3rem;
        }

        .txt-judul-portal {
            font-size: 2.0rem;
            font-weight: 700;
            color: #000000;
            margin-bottom: 3rem;
            text-align: center;
            /* Changed from center to left */
        }

        .romadan-peraturan-detail {
            font-size: 1.75rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 2rem;
            text-align: left;
            /* Changed from center to left */
        }

        /* Modern Search Section */
        .search-container {
            max-width: 800px;
            margin: 0 auto 2.5rem;
            padding: 0 1rem;
        }

        .search-wrapper {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .search-input {
            width: 100%;
            padding: 1rem 1rem 1rem 3rem;
            border: 2px solid #e2e8f0;
            border-radius: 9999px;
            font-size: 1rem;
            background-color: white;
            transition: var(--transition);
        }

        .search-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
            outline: none;
        }

        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
        }

        /* Button Styles */
        .button-group {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        .portal-button {
            padding: 0.75rem 1.5rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-clear {
            background-color: #e2e8f0;
            color: var(--text-primary);
        }

        .btn-clear:hover {
            background-color: #cbd5e1;
        }

        .btn-search {
            background-color: #005FAC;
            color: white;
        }

        .btn-search:hover {
            background-color: #004d8a;
            /* Slightly darker shade for hover */
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 95, 172, 0.2);
        }

        .btn-refresh {
            background-color: #FCB813;
            color: white;
        }

        .btn-refresh:hover {
            background-color: #e5a711;
            /* Slightly darker shade for hover */
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(252, 184, 19, 0.2);
        }

        /* Modern Card Styles */
        .app-card {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            height: 100%;
            border: 1px solid #e2e8f0;
            transition: var(--transition);
        }

        .app-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
        }

        .app-content {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .app-icon {
            width: 64px;
            height: 64px;
            object-fit: contain;
            border-radius: 12px;
        }

        .app-info {
            flex: 1;
        }

        .app-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }

        .app-subtitle {
            font-size: 0.875rem;
            color: var(--text-secondary);
            line-height: 1.4;
        }

        .app-arrow {
            color: var(--primary-color);
            transition: var(--transition);
        }

        /* Loading States */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.8);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .loading-spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 1rem 1.0rem;
            color: var(--text-secondary);
        }

        .empty-state i {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        /* Pagination */
        .pagination-container {
            margin-top: 3rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .main-container {
                padding-top: 80px;
            }

            .txt-judul-infopublik {
                font-size: 2rem;
            }

            .romadan-peraturan-detail {
                font-size: 1.5rem;
            }

            .button-group {
                flex-direction: column;
            }

            .portal-button {
                width: 100%;
            }
        }

    </style>
@endsection

@section('content')
    <div class="main-container">
        <div class="container">
            <!-- Header Section -->
            <div class="title-section-ourmenu">
                <h5 class="txt-judul-infopublik m-t-2">
                    Informasi Publik
                </h5>
            </div>

            <!-- Portal Section -->
            <div class="portal-section">
                <h5 class="txt-judul-portal">
                    Portal Aplikasi
                </h5>

                <!-- Search Form -->
                <div class="search-container">
                    <div class="search-wrapper">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" id="searchInput" class="search-input" placeholder="Cari Aplikasi"
                            value="{{ request('cari_aplikasi') }}">
                    </div>

                    <div class="button-group">
                        {{-- <button type="button" class="portal-button btn-clear" id="clearButton">
                        <i class="fas fa-times"></i> Clear
                    </button> --}}
                        <button type="button" class="portal-button btn-search" id="searchButton">
                            <i class="fas fa-search"></i> Cari
                        </button>
                        <button type="button" class="portal-button btn-refresh" id="refreshButton">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                    </div>
                </div>

                <!-- Applications Grid -->
                <div id="applicationsGrid" class="row">
                    @include('frontend.infopublik.partials.applications-grid')
                </div>

                <!-- Pagination -->
                <div id="paginationContainer" class="pagination-container d-flex justify-content-center">
                    {{ $data->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div class="loading-overlay">
        <div class="loading-spinner"></div>
    </div>
@endsection

@section('script_fe')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchButton = document.getElementById('searchButton');
    const refreshButton = document.getElementById('refreshButton');
    const loadingOverlay = document.querySelector('.loading-overlay');
    const applicationsGrid = document.getElementById('applicationsGrid');
    const paginationContainer = document.getElementById('paginationContainer');

    let isLoading = false;

    const showLoading = () => {
        isLoading = true;
        loadingOverlay.style.display = 'flex';
    };

    const hideLoading = () => {
        isLoading = false;
        loadingOverlay.style.display = 'none';
    };

    const fetchData = async (options = {}) => {
        if (isLoading) return;

        try {
            showLoading();

            const response = await fetch('{{ route("informasi-publik-aplikasi-index-fe") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(options)
            });

            const data = await response.json();

            if (data.status === 'success') {
                applicationsGrid.innerHTML = data.html;
                paginationContainer.innerHTML = data.pagination;

                // Update URL jika ada pencarian
                const url = new URL(window.location);
                if (options.cari_aplikasi) {
                    url.searchParams.set('cari_aplikasi', options.cari_aplikasi);
                } else {
                    url.searchParams.delete('cari_aplikasi');
                }
                window.history.pushState({}, '', url);
            } else {
                throw new Error(data.message || 'Terjadi kesalahan');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memuat data. Silakan coba lagi.');
        } finally {
            hideLoading();
        }
    };

    // Search functionality
    let searchTimeout;
    searchInput.addEventListener('input', (e) => {
        clearTimeout(searchTimeout);
        const searchValue = e.target.value.trim();

        if (searchValue.length >= 3 || searchValue.length === 0) {
            searchTimeout = setTimeout(() => {
                fetchData({ cari_aplikasi: searchValue });
            }, 500);
        }
    });

    // Search button click
    searchButton.addEventListener('click', () => {
        const searchValue = searchInput.value.trim();
        fetchData({ cari_aplikasi: searchValue });
    });

    // Refresh button click
    refreshButton.addEventListener('click', () => {
        searchInput.value = '';
        fetchData({ refresh: true });
    });

    // Pagination click handling
    document.addEventListener('click', (e) => {
        if (e.target.matches('.pagination a')) {
            e.preventDefault();
            const url = e.target.href;
            const searchValue = searchInput.value.trim();
            fetchData({
                cari_aplikasi: searchValue,
                page: (new URL(url)).searchParams.get('page')
            });
        }
    });
});
</script>
@endsection
