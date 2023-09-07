@extends('layouts.webromadan_frontend.fe_master')

@section('css_fe')
<style>
  .input-with-logo {
	background-image: url('{{ asset('frontend_romadan_web/images/search_romadan_peraturan.svg') }}');
	background-size:20px;
	background-position: 10px center;
    background-repeat: no-repeat;
    padding-left: 40px; /* Sesuaikan jarak logo dengan input */
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
					<div class="col-lg-12">
                        <h5 class="romadan-peraturan-detail m-t-2">
                            Portal Aplikasi
                        </h5>

                         <form class="form-outline mt-4" action="{{route('informasi-publik-aplikasi-index-fe')}}" method="POST" autocomplete="off">
						@csrf
                        <div class="wrap-inputname size12 bo2 bo-rad-10 m-t-3 m-b-23">
							<input id="search-input" class="bo-rad-10 sizefull txt10 p-l-20" type="text" name="cari_aplikasi" placeholder="Cari Aplikasi">
						</div>
						<div class="wrap-btn-booking flex-c-m m-t-13">
							<button type="reset" class="btn3-kegiatan-2 flex-c-m size36 txt11 trans-0-4 mr-2">
								Clear
							</button>
							<button type="submit" class="btn3-kegiatan flex-c-m size36 txt11 trans-0-4">
								Cari
							</button>
							<a class="btn3-kegiatan-refresh flex-c-m size36 txt11 trans-0-4 ml-2" href="{{route('informasi-publik-aplikasi-index-fe')}}">Refresh
							</a>
						</div>
					
                    </form>
                        <div class="row">

                          @forelse ($data as $item)
                              <div class="col-lg-4 p-t-30">
                                      <div class="card w-100 border-0">
                                        <div class="card-body">
                                                                <div class="row">
                                                                <div class="col-lg-3">
                                                                <a href="{{$item->link_aplikasi}}" class="d-inline-block">
                                                <img src="{{asset('storage/romadan_gambar_web/'.$item->image)}}" class="" width="70" alt="">
                                                </a>
                                                                </div>
                                                                <div class="col-lg-7">
                                                                <h5 class="card-title romadan-peraturan-judul">{{$item->judul_aplikasi}}</h5>
                                          <p class="card-text romadan-peraturan-subjudul">{{$item->sub_judul_aplikasi}}</p>
                                                                </div>
                                                                <div class="col-lg-2">
                                                                <a href="{{$item->link_aplikasi}}"><i class="fa-solid fa-arrow-right mt-4"></i></a>
                                                                </div>
                                                                </div>
                                          
                                        </div>
                                      </div>
					   	                  </div>   
                          @empty
                             <div class="container pt-5">                         
                                <h5 class="romadan-faq text-center m-t-2">
                                  Mohon maaf link aplikasi tidak ada !
                                </h5>
                              </div>
                          @endforelse
                                   

                        </div>
                        <div class="d-flex justify-content-center mt-5">
                              {!! $data->appends(request()->input())->links() !!}
                        </div>
					</div>
                </div>
            </div>
	</section>

@section('script_fe')
@endsection
      
@endsection