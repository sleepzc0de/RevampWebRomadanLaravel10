@extends('layouts.webromadan_frontend.fe_master')

@section('css_fe')
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

						<div class="t-center m-b-22 " style="text-align: left;">
							<div class="txt-judul-berita-terkini-detail text-center">{{$data->judul}}</div>
						</div>
                        

                        <div class="t-center m-b-22 " style="text-align: justify;">
                            
                             <div class="txt-judul-kegiatan-detail-tanggal">
                                <div class="mt-3 pic-blo4 hov-img-zoom bo-rad-10 pos-relative w-100">
                                        <a href="{{asset('storage/romadan_gambar_web/'.$data->image)}}">
                                            <img src="{{asset('storage/romadan_gambar_web/'.$data->image)}}" alt="IMG-BLOG">
                                        </a>
							        </div>
                               <div class="txt-judul-kegiatan-detail t-center m-b-35 mt-5 px-4" style="text-align: justify;">
                                {!!$data->isi!!}
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