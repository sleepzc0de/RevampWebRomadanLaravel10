@extends('layouts.webromadan_frontend.fe_master')

@section('css_fe')
    <style>
        /* Styling untuk judul */
        .txt-judul-infopublik {
            word-wrap: break-word;
            overflow-wrap: break-word;
            max-width: 100%;
            padding: 0 15px;
            font-size: 1.5rem;
            /* Ukuran font bisa disesuaikan */
            margin-bottom: 20px;
            color: #333;
            /* Warna teks bisa disesuaikan */
            line-height: 1.4;
        }

        /* Styling untuk konten */
        .romadan-infopublik {
            word-wrap: break-word;
            overflow-wrap: break-word;
            max-width: 100%;
            padding: 0 15px;
        }

        .romadan-infopublik img {
            max-width: 100%;
            height: auto;
        }

        .romadan-infopublik p {
            white-space: normal;
            word-wrap: break-word;
        }

.card2-peraturan-index {
    max-width: 100%;
    padding: 20px;
}

.card2-peraturan-index h4 {
    word-wrap: break-word;
    overflow-wrap: break-word;
    font-size: 1.2rem;
    line-height: 1.4;
    margin-bottom: 10px;
    padding-right: 20px;
}

.card2-peraturan-index .small {
    word-wrap: break-word;
    overflow-wrap: break-word;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    line-height: 1.5;
}
    </style>
@endsection


@section('content')
    <section class="section-welcome p-t-120 p-b-105" style="background-color: white;">
        <div class="container">
            <div class="title-section-ourmenu m-b-22">
                <h5 class="txt-judul-infopublik m-t-2">
                    {{ $info_publik->judul ?? 'Informasi Publik' }}
                </h5>
                <br>
            </div>
            <div class="row">

                <div class="romadan-infopublik col-md-12 p-t-30">
                    {!! clean($info_publik->isi ?? '') !!}
                </div>

                @forelse ($infolist as $item)
                    {{-- <div class="col-md-4 p-t-30">
                            <div class="card w-100m bo-rad-10 " style="background-color: #0F5FAE; box-shadow: 6px 6px 4px rgba(0, 0, 0, 0.2);">
                                <div class="card-body infopublik-card-home">
                                    <h5 class="card-title">
                                         <a href="{{ route($item->link_list_informasi) }}"><button type="button" class="btn btn-warning mr-2"><i class="fa-solid fa-house"></i></button></a>
			                    	{{$item->judul_list_informasi}}</h5>
                                    <p class="card-text">Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptates, in?</p>
                                    <a href="{{ route($item->link_list_informasi) }}" class="btn btn-link mt-4">Lihat Semuanya<i class="fa-solid fa-arrow-right ml-3"></i></a>
                                </div>
                            </div>
					    </div> --}}

                        <div class="col-md-4 p-t-30">
                            <a class="card2-peraturan-index" href="{{ route($item->link_list_informasi) }}">
                                <h4>
                                    <button type="button" class="btn btn-warning mr-2">
                                        <i class="fa-solid fa-house"></i>
                                    </button>
                                    {{Str::limit($item->judul_list_informasi, 15)}}
                                </h4>
                                <p class="small mt-2 card-text">
                                    {!! Str::limit(strip_tags($item->isi_list_informasi), 15) !!}
                                </p>
                                <h6 class="mt-2">Lihat Semuanya<i class="fa-solid fa-arrow-right ml-3"></i></h6>
                                <div class="go-corner" href="{{ route($item->link_list_informasi) }}">
                                    <div class="go-arrow-peraturan-index">→</div>
                                </div>
                            </a>
                        </div>


                @empty
                    <section class="section-welcome p-t-120 p-b-105 text-center">
                        <div class="container">
                            <div class="title-section-ourmenu m-b-22">
                                <h5 class="romadan-faq m-t-2">
                                    Tidak ada Data, Harap hubungi Administrator !
                                </h5>
                            </div>

                        </div>

                    </section>
                @endforelse



                {{-- <div class="col-md-4 p-t-30">
                            <div class="card w-100" style="background-color:#0F5FAE;">
                                <div class="card-body infopublik-card-home">
                                    <h5 class="card-title">
			                    	<button type="button" class="btn btn-warning mr-2"><i class="fa-solid fa-house"></i></button>PERATURAN</h5>
                                    <p class="card-text">Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet.</p>
                                    <a href="{{route('informasi-publik-peraturan-index-fe')}}" class="btn btn-link mt-4">Lihat Semuanya<i class="fa-solid fa-arrow-right ml-3"></i></a>
                                </div>
                            </div>
					    </div>
                         <div class="col-md-4 p-t-30">
                            <div class="card w-100" style="background-color:#0F5FAE;">
                                <div class="card-body infopublik-card-home">
                                    <h5 class="card-title">
			                    	<button type="button" class="btn btn-warning mr-2"><i class="fa-solid fa-book"></i></button>PEDOMAN</h5>
                                    <p class="card-text">Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet.</p>
                                    <a href="{{route('informasi-publik-pedoman-index-fe')}}" class="btn btn-link mt-4">Lihat Semuanya<i class="fa-solid fa-arrow-right ml-3"></i></a>
                                </div>
                            </div>
					    </div>
                         <div class="col-md-4 p-t-30">
                            <div class="card w-100" style="background-color:#0F5FAE;">
                                <div class="card-body infopublik-card-home">
                                    <h5 class="card-title">
			                    	<button type="button" class="btn btn-warning mr-2"><i class="fa-solid fa-globe"></i></button>LINK APLIKASI</h5>
                                    <p class="card-text">Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet.</p>
                                    <a href="#" class="btn btn-link mt-4">Lihat Semuanya<i class="fa-solid fa-arrow-right ml-3"></i></a>
                                </div>
                            </div>
					    </div> --}}
            </div>
        </div>
    </section>

    @section('script_fe')
    @endsection
@endsection
