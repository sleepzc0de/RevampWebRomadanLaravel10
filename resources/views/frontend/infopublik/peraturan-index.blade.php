@extends('layouts.webromadan_frontend.fe_master')

@section('css_fe')
    <style>
        .input-with-logo {
            background-image: url('{{ asset('frontend_romadan_web/images/search_romadan_peraturan.svg') }}');
            background-size: 20px;
            background-position: 10px center;
            background-repeat: no-repeat;
            padding-left: 40px;
            /* Sesuaikan jarak logo dengan input */
        }
    </style>
    <style>
        .search-container {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            padding: 24px;
        }

        .search-header {
            margin-bottom: 20px;
        }

        .search-header h5 {
            font-size: 18px;
            color: #1a1a1a;
            margin: 0;
            padding-bottom: 12px;
            border-bottom: 1px solid #eaeaea;
        }

        .search-input-wrapper {
            position: relative;
            margin-bottom: 24px;
        }

        .search-input {
            width: 100%;
            padding: 12px 16px 12px 44px;
            border: 2px solid #eaeaea;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            border-color: #0F5FAE;
            outline: none;
            box-shadow: 0 0 0 3px rgba(15, 95, 174, 0.1);
        }

        .search-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            pointer-events: none;
        }

        .filter-section {
            margin-bottom: 24px;
        }

        .filter-section h5 {
            font-size: 16px;
            color: #1a1a1a;
            margin: 0 0 16px 0;
        }

        .checkbox-group {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .checkbox-wrapper {
            display: flex;
            align-items: center;
            cursor: pointer;
            padding: 8px 12px;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .checkbox-wrapper:hover {
            background: #f5f5f5;
        }

        /* Updated checkbox styles */
        .checkbox-input {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            width: 20px;
            height: 20px;
            border: 2px solid #cccccc;
            /* Lighter border color for better visibility */
            border-radius: 4px;
            margin-right: 12px;
            cursor: pointer;
            position: relative;
            background-color: #fff;
            transition: all 0.2s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            /* Subtle shadow to make it stand out */
        }

        .checkbox-input:hover {
            border-color: #999999;
            /* Darker border on hover */
            background-color: #f8f8f8;
        }

        .checkbox-input:checked {
            background: #0F5FAE;
            border-color: #0F5FAE;
        }

        .checkbox-input:checked::after {
            content: '✓';
            position: absolute;
            color: white;
            font-size: 14px;
            font-weight: bold;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .checkbox-input:focus {
            outline: none;
            border-color: #0F5FAE;
            box-shadow: 0 0 0 3px rgba(15, 95, 174, 0.2);
        }

        .checkbox-label {
            font-size: 14px;
            color: #4a4a4a;
        }

        .button-group {
            display: flex;
            gap: 12px;
        }

        .search-btn {
            flex: 1;
            padding: 12px 24px;
            background: #0F5FAE;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .search-btn:hover {
            background: #0d4d8f;
        }

        .refresh-btn {
            padding: 12px 24px;
            background: white;
            color: #0F5FAE;
            border: 1px solid #0F5FAE;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .refresh-btn:hover {
            background: #f5f8ff;
        }

        .divider {
            height: 1px;
            background: #eaeaea;
            margin: 24px 0;
        }
    </style>
@endsection


@section('content')


    <section class="section-welcome p-t-120 p-b-105" style="background-color: white;">
        <div class="container">
            <div class="title-section-ourmenu m-b-22">
                <h5 class="txt-judul-infopublik m-t-2">
                    Informasi Publik
                </h5>
                <br>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <div class="search-container">
                        <div class="search-header">
                            <h5>REFINE YOUR SEARCH</h5>
                        </div>

                        <form action="{{ route('informasi-publik-peraturan-index-fe') }}" method="POST" autocomplete="off">
                            @csrf
                            <div class="search-input-wrapper">
                                <input name="cari_peraturan" type="text" class="search-input"
                                    placeholder="Cari peraturan disini">
                                <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="#999">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                </svg>
                            </div>

                            <div class="filter-section">
                                <h5>Kategori</h5>
                                <div class="checkbox-group">
                                    @forelse ($kategori as $item)
                                    <label class="checkbox-wrapper">
                                        <input name="kategori[]" type="checkbox" class="checkbox-input"
                                            value="{{ $item->nama_kategori }}"
                                            {{ is_array($selectedKategori) && in_array($item->nama_kategori, $selectedKategori) ? 'checked' : '' }}>
                                        <span class="checkbox-label">
                                            {{ Str::limit(collect(explode(' ', strtolower($item->nama_kategori)))->map(function ($word) {
                                                return strlen($word) <= 3 ? strtoupper($word) : ucfirst($word);
                                            })->join(' '), 10) }}
                                        </span>
                                    </label>
                                @empty
                                    <span class="checkbox-label">Tidak Ada Data</span>
                                @endforelse
                                </div>
                            </div>

                            <div class="divider"></div>

                            <div class="filter-section">
                                <h5>Jenis Peraturan</h5>
                                <div class="checkbox-group">
                                    @forelse ($jenis_peraturan as $item)
                                    <label class="checkbox-wrapper">
                                        <input name="jenis_peraturan[]" type="checkbox" class="checkbox-input"
                                            value="{{ $item->nama_jenis_peraturan }}"
                                            {{ is_array($selectedJenisPeraturan) && in_array($item->nama_jenis_peraturan, $selectedJenisPeraturan) ? 'checked' : '' }}>
                                        <span class="checkbox-label">
                                            {{ Str::limit($item->nama_jenis_peraturan, 10) }}
                                        </span>
                                    </label>
                                @empty
                                    <span class="checkbox-label">Tidak Ada Data</span>
                                @endforelse
                                </div>
                            </div>

                            <div class="button-group">
                                <button type="submit" class="search-btn">
                                    Cari
                                </button>
                                <a href="{{ route('informasi-publik-peraturan-index-fe') }}" class="refresh-btn">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor">
                                        <path
                                            d="M21.5 2v6h-6M2.5 22v-6h6M2 12c0-4.4 3.6-8 8-8 3.4 0 6.3 2.1 7.4 5M22 12c0 4.4-3.6 8-8 8-3.4 0-6.3-2.1-7.4-5" />
                                    </svg>
                                    Refresh
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-8">
                    <h4 class="romadan-peraturan-utama">Daftar Peraturan Tentang Barang Milik Negara & Pengadaan</h4>
                    <div class="row p-t-30">
                        @forelse ($peraturan as $item)
                            <div class="col-md-6">
                                <a class="card3" style="height: 100%;"
                                    href="{{ route('informasi-publik-peraturan-detail-fe', $item->slug) }}">
                                    <h5 class="card-title romadan-peraturan-judul" data-toggle="tooltip" title="{{ $item->nomor_peraturan }}">
                                        {{ Str::limit($item->nomor_peraturan, 20) }}
                                    </h5>
                                    <p class="small" data-toggle="tooltip" title="{{ $item->judul_peraturan }}">
                                        {{ Str::limit($item->judul_peraturan, 30) }}
                                    </p>
                                    <h6 class="mt-2">Lihat Detail<i class="fa-solid fa-arrow-right ml-3"></i></h6>
                                    <div class="dimmer"></div>
                                    <div class="go-corner-card3">
                                        <div class="go-arrow-card3">
                                            →
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @empty
                            <section class="section-welcome p-t-50 p-b-105" style="background-color: white;">
                                <div class="container">
                                    <div class="title-section-ourmenu m-b-22">
                                        <h5 class="romadan-faq m-t-5">
                                            Mohon maaf, data yang anda cari tidak ada :(
                                        </h5>
                                    </div>
                                </div>
                            </section>
                        @endforelse

                        {{-- <div class="col-md-6 p-t-30">
									<div class="card w-100" style="background-color:#0F5FAE;">
										<div class="card-body infopublik-card-home">
											<h5 class="card-title">B</h5>
											<p class="card-text">Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet.</p>
											<a href="#" class="btn btn-link mt-1">Lihat Semuanya<i class="fa-solid fa-arrow-right ml-3"></i></a>
										</div>
									</div>
					   		 </div> --}}


                    </div>
                    <div class="d-flex justify-content-center mt-5">
                        {!! $peraturan->appends(request()->input())->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </section>

    @section('script_fe')
        {{-- <script>
    function submitForm() {
        document.getElementById('filterForm').submit();
    }
</script> --}}
    @endsection

@endsection
