@extends('layouts.webromadan_frontend.fe_master')

@section('css_fe')
<style>
:root {
    --primary-color: #0F5FAE;
    --primary-dark: #0d4d8f;
    --primary-light: #e6f0ff;
    --accent-color: #FF9900;
    --text-dark: #2d3748;
    --text-light: #718096;
    --bg-light: #f7fafc;
    --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    --transition: all 0.3s ease;
    --radius-sm: 0.375rem;
    --radius-md: 0.5rem;
    --radius-lg: 0.75rem;
}

/* Header Styling */
.txt-judul-infopublik {
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 0.5rem;
}

.blue-line {
    width: 80px;
    height: 4px;
    background-color: var(--primary-color);
    margin-top: 1rem;
    margin-bottom: 2rem;
    border-radius: 2px;
}

.section-header {
    position: relative;
    margin-bottom: 2.5rem;
    padding-bottom: 1rem;
}

.section-header::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 80px;
    height: 4px;
    background: var(--primary-color);
    border-radius: 2px;
}

.section-title {
    color: var(--text-dark);
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.section-subtitle {
    color: var(--text-light);
    font-size: 1.1rem;
}

/* Filter Panel */
.filter-panel {
    background-color: white;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-md);
    padding: 1.5rem;
    height: 100%;
    position: sticky;
    top: 2rem;
    transition: var(--transition);
}

.filter-panel:hover {
    box-shadow: var(--shadow-lg);
}

.filter-header {
    display: flex;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e2e8f0;
}

.filter-header i {
    color: var(--primary-color);
    font-size: 1.2rem;
    margin-right: 0.75rem;
}

.filter-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--text-dark);
    margin: 0;
}

.search-wrapper {
    position: relative;
    margin-bottom: 1.5rem;
}

.search-input {
    width: 100%;
    padding: 0.75rem 1rem 0.75rem 3rem;
    border: 2px solid #e2e8f0;
    border-radius: var(--radius-md);
    font-size: 0.95rem;
    transition: var(--transition);
    color: var(--text-dark);
}

.search-input:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(15, 95, 174, 0.1);
    outline: none;
}

.search-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-light);
    font-size: 1.1rem;
}

.filter-section {
    margin-bottom: 1.5rem;
}

.filter-section-title {
    display: flex;
    align-items: center;
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 1rem;
}

.filter-section-title i {
    color: var(--primary-color);
    margin-right: 0.5rem;
    font-size: 0.9rem;
}

/* Custom Checkboxes */
.custom-checkbox-group {
    display: grid;
    gap: 0.75rem;
}

.custom-checkbox {
    display: flex;
    align-items: center;
    padding: 0.5rem 0.75rem;
    border-radius: var(--radius-sm);
    cursor: pointer;
    transition: var(--transition);
}

.custom-checkbox:hover {
    background-color: var(--primary-light);
}

.custom-checkbox input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
    height: 0;
    width: 0;
}

.checkmark {
    position: relative;
    height: 20px;
    width: 20px;
    background-color: #fff;
    border: 2px solid #cbd5e0;
    border-radius: 4px;
    margin-right: 0.75rem;
    transition: var(--transition);
}

.custom-checkbox:hover input ~ .checkmark {
    border-color: var(--primary-color);
}

.custom-checkbox input:checked ~ .checkmark {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
}

.checkmark:after {
    content: "";
    position: absolute;
    display: none;
}

.custom-checkbox input:checked ~ .checkmark:after {
    display: block;
}

.custom-checkbox .checkmark:after {
    left: 6px;
    top: 2px;
    width: 5px;
    height: 10px;
    border: solid white;
    border-width: 0 2px 2px 0;
    transform: rotate(45deg);
}

.custom-checkbox .checkbox-label {
    font-size: 0.95rem;
    color: var(--text-dark);
    transition: var(--transition);
}

.custom-checkbox input:checked ~ .checkbox-label {
    color: var(--primary-color);
    font-weight: 500;
}

.divider {
    height: 1px;
    background-color: #e2e8f0;
    margin: 1.5rem 0;
}

.btn-group {
    display: flex;
    gap: 0.75rem;
}

.btn-primary {
    flex: 1;
    padding: 0.75rem 1.25rem;
    background-color: var(--primary-color);
    color: white;
    border: none;
    border-radius: var(--radius-md);
    font-weight: 500;
    cursor: pointer;
    transition: var(--transition);
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-primary:hover {
    background-color: var(--primary-dark);
    transform: translateY(-2px);
}

.btn-secondary {
    padding: 0.75rem 1.25rem;
    background-color: white;
    color: var(--primary-color);
    border: 1px solid var(--primary-color);
    border-radius: var(--radius-md);
    font-weight: 500;
    cursor: pointer;
    transition: var(--transition);
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-secondary:hover {
    background-color: var(--primary-light);
    transform: translateY(-2px);
}

.btn-secondary i {
    margin-right: 0.5rem;
}

/* Peraturan Cards */
.peraturan-list-container {
    padding-left: 1.5rem;
}

.peraturan-header {
    display: flex;
    align-items: center;
    margin-bottom: 1.5rem;
}

.peraturan-title {
    color: var(--text-dark);
    font-size: 1.5rem;
    font-weight: 700;
    margin: 0;
}

.peraturan-count {
    margin-left: 0.75rem;
    padding: 0.25rem 0.75rem;
    background-color: var(--primary-light);
    color: var(--primary-color);
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
}

.peraturan-card {
    display: flex;
    flex-direction: column;
    background-color: white;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
    transition: var(--transition);
    height: 100%;
    position: relative;
    margin-bottom: 1.5rem;
}

.peraturan-card:hover {
    box-shadow: var(--shadow-lg);
    transform: translateY(-5px);
}

.peraturan-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background-color: var(--primary-color);
    opacity: 0;
    transition: var(--transition);
}

.peraturan-card:hover::before {
    opacity: 1;
}

.peraturan-card-body {
    padding: 1.5rem;
    flex: 1;
}

.peraturan-card-category {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    background-color: var(--primary-light);
    color: var(--primary-color);
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    margin-bottom: 1rem;
}

.peraturan-card-number {
    color: var(--text-dark);
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 0.75rem;
    line-height: 1.4;
}

.peraturan-card-content {
    color: var(--text-light);
    font-size: 0.95rem;
    margin-bottom: 1.5rem;
    line-height: 1.6;
}

.peraturan-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem 1.5rem;
    background-color: #f8fafc;
    border-top: 1px solid #edf2f7;
}

.peraturan-card-date {
    display: flex;
    align-items: center;
    color: var(--text-light);
    font-size: 0.85rem;
}

.peraturan-card-date i {
    font-size: 0.85rem;
    margin-right: 0.5rem;
}

.peraturan-card-link {
    display: flex;
    align-items: center;
    color: var(--primary-color);
    font-size: 0.95rem;
    font-weight: 600;
    transition: var(--transition);
}

.peraturan-card-link:hover {
    color: var(--primary-dark);
    text-decoration: none;
}

.peraturan-card-link i {
    margin-left: 0.5rem;
    transition: var(--transition);
}

.peraturan-card-link:hover i {
    transform: translateX(3px);
}

/* Pagination */
.custom-pagination {
    display: flex;
    justify-content: center;
    margin-top: 2.5rem;
}

.custom-pagination .page-item {
    margin: 0 0.25rem;
}

.custom-pagination .page-link {
    padding: 0.5rem 0.75rem;
    border-radius: var(--radius-md);
    border: 1px solid #e2e8f0;
    color: var(--text-dark);
    transition: var(--transition);
}

.custom-pagination .page-link:hover {
    background-color: var(--primary-light);
    color: var(--primary-color);
    border-color: var(--primary-light);
}

.custom-pagination .page-item.active .page-link {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
    color: white;
}

/* Empty state */
.empty-state {
    text-align: center;
    padding: 3rem 0;
}

.empty-state i {
    font-size: 3rem;
    color: #cbd5e0;
    margin-bottom: 1.5rem;
}

.empty-state-title {
    font-size: 1.5rem;
    color: var(--text-dark);
    margin-bottom: 1rem;
}

.empty-state-text {
    color: var(--text-light);
    max-width: 400px;
    margin: 0 auto;
}

/* Animations */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.animate-fade-in {
    animation: fadeIn 0.5s ease-out forwards;
}

/* Responsive */
@media (max-width: 992px) {
    .peraturan-list-container {
        padding-left: 0;
        margin-top: 2rem;
    }

    .filter-panel {
        position: relative;
        top: 0;
    }
}

@media (max-width: 768px) {
    .section-title {
        font-size: 1.75rem;
    }

    .section-subtitle {
        font-size: 1rem;
    }

    .peraturan-title {
        font-size: 1.25rem;
    }
}

@media (max-width: 576px) {
    .btn-group {
        flex-direction: column;
    }

    .btn-secondary {
        width: 100%;
    }
}

/* Tooltip styles */
.tooltip {
    position: relative;
    display: inline-block;
}

.tooltip .tooltiptext {
    visibility: hidden;
    width: 200px;
    background-color: #333;
    color: #fff;
    text-align: center;
    border-radius: 6px;
    padding: 5px;
    position: absolute;
    z-index: 1;
    bottom: 125%;
    left: 50%;
    margin-left: -100px;
    opacity: 0;
    transition: opacity 0.3s;
}

.tooltip .tooltiptext::after {
    content: "";
    position: absolute;
    top: 100%;
    left: 50%;
    margin-left: -5px;
    border-width: 5px;
    border-style: solid;
    border-color: #333 transparent transparent transparent;
}

.tooltip:hover .tooltiptext {
    visibility: visible;
    opacity: 1;
}

/* Animated elements */
.animated-element {
    transition: var(--transition);
}

.animated-element:hover {
    transform: scale(1.02);
}
</style>
@endsection

@section('content')
<section class="section-welcome p-t-120 p-b-105" style="background-color: #f7fafc;">
    <div class="container">
        <div class="title-section-ourmenu m-b-22">
            <h5 class="txt-judul-layanan m-t-2">
                Informasi Publik
            </h5>
            <p class="mt-2 text-muted">
                Akses peraturan dan regulasi terkait Barang Milik Negara & Pengadaan
            </p>
            <div class="blue-line"></div>
        </div>

        <div class="row">
            <!-- Filter Panel -->
            <div class="col-lg-4 animate-fade-in" style="animation-delay: 0.1s;">
                <div class="filter-panel">
                    <div class="filter-header">
                        <i class="fa-solid fa-sliders"></i>
                        <h3 class="filter-title">REFINE YOUR SEARCH</h3>
                    </div>

                    <form action="{{ route('informasi-publik-peraturan-index-fe') }}" method="POST" autocomplete="off">
                        @csrf
                        <div class="search-wrapper">
                            <i class="fa-solid fa-search search-icon"></i>
                            <input
                                type="text"
                                name="cari_peraturan"
                                class="search-input"
                                placeholder="Cari peraturan..."
                                value="{{ request('cari_peraturan') ?? '' }}"
                            >
                        </div>

                        <div class="filter-section">
                            <h4 class="filter-section-title">
                                <i class="fa-solid fa-tag"></i>
                                Kategori
                            </h4>
                            <div class="custom-checkbox-group">
                                @forelse ($kategori as $item)
                                <label class="custom-checkbox animated-element">
                                    <input
                                        type="checkbox"
                                        name="kategori[]"
                                        value="{{ $item->nama_kategori }}"
                                        {{ is_array($selectedKategori) && in_array($item->nama_kategori, $selectedKategori) ? 'checked' : '' }}
                                    >
                                    <span class="checkmark"></span>
                                    <span class="checkbox-label">
                                        {{ Str::limit(collect(explode(' ', strtolower($item->nama_kategori)))->map(function ($word) {
                                            return strlen($word) <= 3 ? strtoupper($word) : ucfirst($word);
                                        })->join(' '), 30) }}
                                    </span>
                                </label>
                                @empty
                                <div class="text-center p-3">
                                    <i class="fa-solid fa-folder-open text-muted"></i>
                                    <p class="mt-2 mb-0">Tidak ada kategori</p>
                                </div>
                                @endforelse
                            </div>
                        </div>

                        <div class="divider"></div>

                        <div class="filter-section">
                            <h4 class="filter-section-title">
                                <i class="fa-solid fa-file-lines"></i>
                                Jenis Peraturan
                            </h4>
                            <div class="custom-checkbox-group">
                                @forelse ($jenis_peraturan as $item)
                                <label class="custom-checkbox animated-element">
                                    <input
                                        type="checkbox"
                                        name="jenis_peraturan[]"
                                        value="{{ $item->nama_jenis_peraturan }}"
                                        {{ is_array($selectedJenisPeraturan) && in_array($item->nama_jenis_peraturan, $selectedJenisPeraturan) ? 'checked' : '' }}
                                    >
                                    <span class="checkmark"></span>
                                    <span class="checkbox-label">
                                        {{ Str::limit($item->nama_jenis_peraturan, 30) }}
                                    </span>
                                </label>
                                @empty
                                <div class="text-center p-3">
                                    <i class="fa-solid fa-folder-open text-muted"></i>
                                    <p class="mt-2 mb-0">Tidak ada jenis peraturan</p>
                                </div>
                                @endforelse
                            </div>
                        </div>

                        <div class="btn-group mt-4">
                            <button type="submit" class="btn-primary">
                                <i class="fa-solid fa-search mr-2"></i>
                                Cari
                            </button>
                            <a href="{{ route('informasi-publik-peraturan-index-fe') }}" class="btn-secondary">
                                <i class="fa-solid fa-rotate-right"></i>
                                Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Peraturan List -->
            <div class="col-lg-8">
                <div class="peraturan-list-container animate-fade-in" style="animation-delay: 0.2s;">
                    <div class="peraturan-header">
                        <h2 class="peraturan-title">Daftar Peraturan</h2>
                        <span class="peraturan-count">{{ $peraturan->total() }} peraturan</span>
                    </div>

                    <div class="row">
                        @forelse ($peraturan as $index => $item)
                        <div class="col-md-6 animate-fade-in mt-2" style="animation-delay: {{ 0.3 + ($index * 0.1) }}s;">
                            <div class="peraturan-card">
                                <div class="peraturan-card-body">
                                    {{-- @if(isset($item->kategori))
                                    <span class="peraturan-card-category">{{ $item->kategori }}</span>
                                    @endif --}}

                                    <h3 class="peraturan-card-number" data-toggle="tooltip" title="{{ $item->nomor_peraturan }}">
                                        {{ Str::limit($item->nomor_peraturan, 30) }}
                                    </h3>

                                    <p class="peraturan-card-content" data-toggle="tooltip" title="{{ $item->judul_peraturan }}">
                                        {{ Str::limit($item->judul_peraturan, 100) }}
                                    </p>
                                </div>

                                <div class="peraturan-card-footer">
                                    @if(isset($item->tanggal_penetapan))
                                    <div class="peraturan-card-date">
                                        <i class="fa-regular fa-calendar"></i>
                                        {{ \Carbon\Carbon::parse($item->tanggal_penetapan)->format('d M Y') }}
                                    </div>
                                    @endif

                                    <a href="{{ route('informasi-publik-peraturan-detail-fe', $item->slug) }}" class="peraturan-card-link">
                                        Lihat Detail
                                        <i class="fa-solid fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="empty-state animate-fade-in">
                                <i class="fa-solid fa-file-circle-xmark"></i>
                                <h3 class="empty-state-title">Tidak Ada Data</h3>
                                <p class="empty-state-text">Mohon maaf, data yang anda cari tidak ada :(</p>
                                <p class="empty-state-text">Silakan gunakan filter atau kata kunci yang berbeda.</p>
                            </div>
                        </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if($peraturan->count() > 0)
                    <div class="custom-pagination animate-fade-in" style="animation-delay: 0.5s;">
                        {!! $peraturan->appends(request()->input())->links() !!}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('script_fe')
<script>
    $(document).ready(function() {
        // Initialize tooltips
        $('[data-toggle="tooltip"]').tooltip();

        // Smooth scrolling for anchor links
        $('a[href*="#"]').on('click', function(e) {
            e.preventDefault();
            $('html, body').animate(
                {
                    scrollTop: $($(this).attr('href')).offset().top - 100,
                },
                500,
                'linear'
            );
        });

        // Animated entrance for cards
        function animateCards() {
            $('.peraturan-card').each(function(index) {
                setTimeout(() => {
                    $(this).addClass('animate-fade-in');
                }, index * 100);
            });
        }

        // Filter panel sticky behavior
        function stickyFilter() {
            const filterPanel = $('.filter-panel');
            const headerHeight = 120; // Adjust based on your header height

            if ($(window).width() > 992) {
                const scrollTop = $(window).scrollTop();
                const sectionTop = $('.section-welcome').offset().top;

                if (sectionTop && scrollTop > sectionTop - headerHeight) {
                    filterPanel.css({
                        'position': 'sticky',
                        'top': headerHeight + 'px'
                    });
                } else {
                    filterPanel.css({
                        'position': 'relative',
                        'top': '0'
                    });
                }
            } else {
                filterPanel.css({
                    'position': 'relative',
                    'top': '0'
                });
            }
        }

        // Call functions
        animateCards();

        // Initialize tooltips and other features with a slight delay
        setTimeout(function() {
            try {
                // Initialize sticky behavior
                stickyFilter();

                // Event listeners
                $(window).on('scroll', function() {
                    stickyFilter();
                });

                $(window).on('resize', function() {
                    stickyFilter();
                });
            } catch (e) {
                console.warn("Non-critical UI enhancement failed to initialize", e);
            }
        }, 500);
    });
</script>
@endsection
