@extends('layouts.webromadan_frontend.fe_master')

@section('css_fe')
@endsection

@section('content')
   <section class="section-welcome p-t-120 p-b-105" style="background-color: white;">
		<div class="container">
				<div class="col-lg-12">
					<div class="wrap-pic-welcome size2 bo-rad-10 hov-img-zoom m-l-r-auto">
						<div class="publikasi-home">Warta</div>
					</div>
				</div>
                <div class="col-lg-12">
					<div class="wrap-pic-welcome size2 bo-rad-10 hov-img-zoom m-l-r-auto">
						<div class="publikasi-home-sub">The latest industry news, interviews, technologies, and resources.</div>
					</div>

					<form class="form-outline mt-5" action="{{route('publikasi-index-warta-fe')}}" method="POST" autocomplete="off">
						@csrf
                        <div class="wrap-inputname size12 bo2 bo-rad-10 m-t-3 m-b-23">
							<input class="bo-rad-10 sizefull txt10 p-l-20" type="text" name="cari_warta" placeholder="Cari Warta">
						</div>
						{{-- <div class="wrap-btn-booking flex-c-m m-t-13">
							<button type="reset" class="btn3-artikel-2 flex-c-m size36 txt11 trans-0-4 mr-2">
								Clear
							</button>
							<button type="submit" class="btn3-artikel flex-c-m size36 txt11 trans-0-4">
								Cari
							</button>
							<a class="btn3-artikel-refresh flex-c-m size36 txt11 trans-0-4 ml-2" href="{{route('publikasi-index-berita-fe')}}">Refresh
							</a>
						</div> --}}

						<div class="col-lg-12 text-center">
							<a class="btn btn-light pilihan-kategori-menu" href="{{route('publikasi-index-warta-fe')}}">View All
							</a>           
            
                            @foreach ($kategori as $item)
                                @php
                                    $currentURL = Request::url();
                                    $selectedCategory = strtolower($item->nama_kategori);
                                    $isActive = $currentURL === route('warta-kategori-fe', $selectedCategory);
                                @endphp

                                <a id="{{ $item->nama_kategori }}" class="btn btn-light pilihan-kategori-menu {{ $isActive ? 'active' : '' }}" href="{{ route('warta-kategori-fe', $selectedCategory) }}">
                                    {{ $item->nama_kategori }}
                                </a>
                            @endforeach



							
						</div>
                    </form>
				</div>
				<div class="col-lg-12 mt-5">
					<div class="row">
					@forelse ($warta as $item)
						<div class="col-md-4 p-t-30">
						<!-- Block1 -->
						<div class="blo4">
							<div class="pic-blo4 hov-img-zoom bo-rad-10 pos-relative">
								<a href="{{asset('storage/romadan_gambar_web/'.$item->image)}}">
									<img src="{{asset('storage/romadan_gambar_web/'.$item->image)}}" height="250px" alt="IMG-BLOG">
								</a>

								<div class="date-blo4-romadan flex-col-c-m">
									<span class="txt30-romadan m-b-4">
										{{date('d', strtotime($item->created_at))}}
									</span>

									<span class="txt31">
										{{date('M, Y', strtotime($item->created_at))}}
									</span>
								</div>
							</div>
							

							<div class="text-blo4 p-t-33">
								<div class="txt32 flex-w p-b-24">
									<span>
										{{date('d F, Y', strtotime($item->created_at))}}
										<span class="m-r-6 m-l-4">|</span>
									</span>

									<span>
										{{$item->nama_kategori}}
										<span class="m-r-6 m-l-4"></span>
									</span>
								</div>
								<div>
									<a href="{{route('warta-fe', $item->slug)}}" class="berita-terkini-judul-romadan">{{$item->judul}}</a>
								</div>



								
							</div>
						</div>

						
						
					</div>
					
					@empty
						<section class="section-welcome p-t-50 p-b-105" style="background-color: white;">

                                <div class="container">
									
                                    <div class="title-section-ourmenu m-b-22">
											{{-- <h3 class="m-b-2"> Anda sedang mencari : "{{$searchValue}}"</h3> --}}
                                            <h5 class="romadan-faq m-t-5">
                                                Mohon maaf, data yang Bapak/Ibu cari belum tersedia :(
                                            </h5>
                                    </div>
                                    
                                </div>
                            
                            </section>
						
					@endforelse
					

				</div>

				<div class="d-flex justify-content-center mt-5">
              {!! $warta->appends(request()->input())->links() !!}
        </div>
		</div>
	</section>

@section('script_fe')
@endsection

@endsection