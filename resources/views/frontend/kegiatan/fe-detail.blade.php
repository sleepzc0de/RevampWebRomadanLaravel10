@extends('layouts.webromadan_frontend.fe_master')

@section('css_fe')
@endsection


@section('content')


	<section class="section-welcome p-t-120 p-b-105" style="background-color: white;">
		<div class="container">
			<div class="row p-t-10">
				
				<div class="col-md-12">
					<div class="wrap-text-welcome">
						<div class="txt-judul-layanan t-center m-b-35 m-t-5" style="text-align: justify;">
							Kegiatan
                        </div>
                        

						<div class="t-center m-b-22 size3 " style="text-align: justify;">
							<div class="txt-judul-kegiatan-detail">{{$data->judul}}</div>
						</div>

                        <div class="t-center m-b-22 size3 " style="text-align: justify;">
                            @if ($data->tanggal_selesai == null)
                            <div class="txt-judul-kegiatan-detail-tanggal">
                                <i class="fa-solid fa-calendar-days mr-2"></i> {{date('d F Y', strtotime($data->tanggal_mulai))}}
                            <br>
                                <i class="fa-solid fa-location-dot mt-3 mr-2"></i> {{$data->tempat}}
                            <br>
                                <img class="mt-3 pic-blo4 hov-img-zoom bo-rad-10 pos-relative" src="{{asset('storage/romadan_gambar_web/'.$data->image)}}" alt="IMG-KEGIATAN-DETAIL" width="1200" height="600">
                            <br>
                               <div class="txt-judul-kegiatan-detail t-center m-b-35 m-t-3" style="text-align: justify;">
                                {!!$data->isi!!}
                               </div>
                            </div>

                            @else
                            <div class="txt-judul-kegiatan-detail-tanggal">
                                <i class="fa-solid fa-calendar-days mr-2"></i> {{date('d', strtotime($data->tanggal_mulai))}} - {{date('d F Y', strtotime($data->tanggal_selesai))}}
                            <br>
                                <i class="fa-solid fa-location-dot mt-3 mr-2"></i> {{$data->tempat}}
                            </div>
                                
                            @endif
							
						</div>
						
											
					</div>
				</div>
                

			</div>
		</div>
	</section>
@section('script_fe')
@endsection

@endsection