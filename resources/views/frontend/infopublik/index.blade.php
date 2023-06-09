@extends('layouts.webromadan_frontend.fe_master')

@section('css_fe')
@endsection


@section('content')


	<section class="section-welcome p-t-120 p-b-105" style="background-color: white;">
		<div class="container">
            <div class="title-section-ourmenu m-b-22">
					<h5 class="romadan-berita m-t-2">
						{{$info_publik->judul}}
					</h5>
                    <br>
			</div>
			<div class="row">
					
						<div class="romadan-infopublik col-md-12 p-t-30">
						{!!$info_publik->isi!!}
					    </div>

                        @forelse ($infolist as $item)

                         <div class="col-md-4 p-t-30">
                            <div class="card w-100" style="background-color:#0F5FAE;">
                                <div class="card-body infopublik-card-home">
                                    <h5 class="card-title">
			                    	<button type="button" class="btn btn-warning mr-2"><i class="fa-solid fa-house"></i></button>{{$item->judul_list_informasi}}</h5>
                                    <p class="card-text">Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet.</p>
                                    <a href="{{ route($item->link_list_informasi) }}" class="btn btn-link mt-4">Lihat Semuanya<i class="fa-solid fa-arrow-right ml-3"></i></a>
                                </div>
                            </div>
					    </div>
                        
                        @empty
<section class="section-welcome p-t-120 p-b-105 text-center" >
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