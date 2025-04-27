@extends('layouts.webromadan_frontend.fe_master')

@section('css_fe')
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

                    <div class="t-center m-b-22" style="text-align: justify;">
                        <div class="txt-judul-kegiatan-detail-tanggal">
                            <div class="mt-3 pic-blo4 hov-img-zoom bo-rad-10 pos-relative w-100">
                                <a href="{{asset('storage/romadan_gambar_web/'.$data->image)}}">
                                    <img src="{{asset('storage/romadan_gambar_web/'.$data->image)}}" alt="IMG-BLOG">
                                </a>
                            </div>
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
@section('script_fe')
@endsection
@endsection
