@extends('layouts.webromadan_frontend.fe_master')

@section('css_fe')
@endsection


@section('content')



{{-- @if ($faq->count() <= 0)

<section class="section-welcome p-t-120 p-b-105" style="background-color: white;">
		<div class="container">
            <div class="title-section-ourmenu m-b-22">
					<h5 class="romadan-faq m-t-2">
						Data Frequently Asked Question Kosong, Harap hubungi Administrator !
					</h5>
			</div>
			
		</div>
	
	</section>
    

@else

<section class="section-welcome p-t-120 p-b-105" style="background-color: white;">
		<div class="container">
            <div class="title-section-ourmenu m-b-22">
					<h5 class="romadan-berita m-t-2">
						Frequently Asked Question
					</h5>
			</div>

            <div class="row p-t-10">
				@forelse ($layanan as $item)
				<div class="col-md-6 ">
					<div class="wrap-text-welcome">
						<div class="txt-judul-layanan t-center m-b-35 m-t-5" style="text-align: justify;">
							{{$item->judul}}
                        </div>

						<div class="t-center m-b-22 size3 " style="text-align: justify;">
							<div class="txt-layanan">{!!$item->layanan!!}</div>
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


@endif --}}


<section class="section-welcome p-t-120 p-b-105" style="background-color: white;">
		<div class="container">

            <div class="title-section-ourmenu m-b-2">
					<h5 class="txt-judul-faq  m-t-2">
						Frequently Asked Questions
					</h5>
			</div>
            
			<div class="row p-t-10 mt-5">
				@forelse ($faq as $item)
				<div class="col-md-6 ">
					<div class="wrap-text-welcome">
						<div class="txt-judul-faq-pertanyaan t-center m-b-35 m-t-5" style="text-align: justify;">
							{{$item->faq_judul}}
                        </div>

						<div class="t-center m-b-22 size3 " style="text-align: justify;">
							<div class="txt-layanan">{!!$item->faq_isi!!}</div>
						</div>
						
											
					</div>
				</div>
				@empty
							<section class="section-welcome p-t-120 p-b-105" style="background-color: white;">
                                <div class="container">
                                    <div class="title-section-ourmenu m-b-22">
                                            <h5 class="romadan-faq m-t-2">
                                                Tidak ada Data, Harap hubungi Administrator !
                                            </h5>
                                    </div>
                                    
                                </div>
                            
                            </section>
				@endforelse
			</div>
		</div>
	</section>
	

@section('script_fe')
@endsection

@endsection