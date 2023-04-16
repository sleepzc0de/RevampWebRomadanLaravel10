@extends('layouts.webromadan_frontend.fe_master')

@section('css_fe')
@endsection


@section('content')


	<section class="section-welcome p-t-120 p-b-105" style="background-color: white;">
		<div class="container">
			<div class="row p-t-10">
				@forelse ($tentang as $item)
				<div class="col-md-6 ">
					<div class="wrap-text-welcome">
						<div class="txt-judul-sejarah t-center m-b-35 m-t-5" style="text-align: justify;">
							{{$item->judul}}
                        </div>

						<div class="t-center m-b-22 size3 " style="text-align: justify;">
							<div class="txt-sejarah">{!!$item->tentang!!}</div>
						</div>
						
											
					</div>
				</div>

				<div class="col-md-6 p-t-90">
					<div class="wrap-pic-welcome size2-visi-misi bo-rad-10 hov-img-zoom m-l-r-auto">
						<a href="{{asset('storage/romadan_gambar_web/' . $item->image)}}"><img src="{{asset('storage/romadan_gambar_web/' . $item->image)}}" alt="IMG-OUR"></a>
					</div>
				</div>

				@empty
							KOSONG
				@endforelse
			</div>
		</div>
	</section>

@section('script_fe')
@endsection

@endsection