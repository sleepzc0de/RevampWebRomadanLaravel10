@extends('layouts.webromadan_frontend.fe_master')

@section('css_fe')
@endsection


@section('content')


	<section class="section-welcome p-t-120 p-b-105" style="background-color: white;">
		<div class="container">
            <div class="title-section-ourmenu m-b-22">
					<h5 class="txt-judul-kegiatan m-t-2">
						Kegiatan
					</h5>
                    <br>
					
                    <form class="form-outline" action="{{route('kegiatan-index-fe')}}" method="POST" autocomplete="off">
						@csrf
                        <div class="wrap-inputname size12 bo2 bo-rad-10 m-t-3 m-b-23">
							<input class="bo-rad-10 sizefull txt10 p-l-20" type="text" name="cari_kegiatan" placeholder="Cari Kegiatan">
						</div>
						<div class="wrap-btn-booking flex-c-m m-t-13">
							<button type="reset" class="btn3-kegiatan-2 flex-c-m size36 txt11 trans-0-4 mr-2">
								Clear
							</button>
							<button type="submit" class="btn3-kegiatan flex-c-m size36 txt11 trans-0-4">
								Cari
							</button>
							<a class="btn3-kegiatan-refresh flex-c-m size36 txt11 trans-0-4 ml-2" href="{{route('kegiatan-index-fe')}}">Refresh
							</a>
						</div>
					
                    </form>
			</div>	
				
			<div class="row">
					@forelse ($kegiatan as $item)
						<div class="col-md-4 p-t-30">
						<!-- Block1 -->
						<div class="blo4">
							<div class="pic-blo4 hov-img-zoom bo-rad-10 pos-relative">
								<a href="{{route('kegiatan-detail-fe', $item->slug)}}">
									<img src="{{asset('storage/romadan_gambar_web/'.$item->image)}}" alt="IMG-BLOG" width="300" height="250">
								</a>

								{{-- <div class="date-blo4-romadan flex-col-c-m">
									<span class="txt30-romadan m-b-4">
										{{date('d', strtotime($item->created_at))}}
									</span>

									<span class="txt31">
										{{date('M, Y', strtotime($item->created_at))}}
									</span> --}}
								</div>
							</div>
							

							<div class="text-blo4 p-t-10">
								<div class="txt32 flex-w p-b-4">
									<span>
										{{date('d F Y', strtotime($item->created_at))}}
									</span>
								</div>
								<div>
									<a href="{{route('kegiatan-detail-fe', $item->slug)}}" class="berita-terkini-judul-romadan">{{$item->judul}}</a>
								</div>



								
							</div>
						</div>
					</div>
					@empty
					<section class="section-welcome p-t-50 p-b-105" style="background-color: white;">

                                <div class="container">
									
                                    <div class="title-section-ourmenu m-b-22">
											<h3 class="m-b-2"> Anda sedang mencari : "{{$searchValue}}"</h3>
                                            <h5 class="romadan-faq m-t-5">
                                                Mohon maaf, data yang anda cari tidak ada :(
                                            </h5>
                                    </div>
                                    
                                </div>
                            
                            </section>
						
					@endforelse

				</div>
		<div class="d-flex justify-content-center mt-5">
              {!! $kegiatan->appends(request()->input())->links() !!}
        </div>
            </div>
	</section>

@section('script_fe')
@endsection

@endsection