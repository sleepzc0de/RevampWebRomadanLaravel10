
@extends('layouts.webromadan_frontend.fe_master')

@section('css_fe')
@endsection

@section('content')
   
	<section class="section-welcome p-t-120 p-b-105" style="background-color: white;">
		<div class="container">
				<div class="col-lg-12">
					<div class="wrap-pic-welcome size2 bo-rad-10 hov-img-zoom m-l-r-auto">
						<div class="publikasi-home">Publikasi</div>
					</div>
				</div>
                <div class="col-lg-12">
					<div class="wrap-pic-welcome size2 bo-rad-10 hov-img-zoom m-l-r-auto">
						<div class="publikasi-home-sub">The latest industry news, interviews, technologies, and resources.</div>
					</div>
				</div>
		</div>
	</section>
    {{-- BERITA TERKINI --}}
    <section class="section-welcome p-b-105" style="background-color: white;">
		<div class="container">
				<div class="col-lg-12">
					<div class="wrap-pic-welcome size2 bo-rad-10 hov-img-zoom m-l-r-auto">
						<div class="publikasi-home-berita-terkini">Berita Terkini</div>
					</div>
				</div>
                <div class="col-lg-12">
					<div class="row">
					@forelse ($berita_terkini_publikasi as $item)
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
									<a href="{{route('berita-fe', $item->slug)}}" class="berita-terkini-judul-romadan">{{$item->judul}}</a>
								</div>



								
							</div>
						</div>

						
						
					</div>
					
					@empty
							<div class="container">                         
						<h5 class="romadan-faq m-t-2">
							Berita Terkini Kosong !
						</h5>
							  </div>
						
					@endforelse
					<div class="col-md-12 p-t-20 romadan-berita-button-home">
						<a href="{{route('publikasi-index-berita-fe')}}" class="btn-tentang-romadan flex-c-m size1 txt3-romadan trans-0-4 mt-3">
								Lihat Selengkapnya<i class="ml-3 fa fa-arrow-right"
								aria-hidden="true"></i>
							</a>
					</div>
					

				</div>
				</div>
		</div>
	</section>
    {{-- WARTA --}}
    <section class="section-welcome p-b-105" style="background-color: white;">
		<div class="container">
				<div class="col-lg-12">
					<div class="wrap-pic-welcome size2 bo-rad-10 hov-img-zoom m-l-r-auto">
						<div class="publikasi-home-warta">Warta</div>
					</div>
				</div>
                <div class="col-lg-12">
					<div class="row">
					@forelse ($warta_terkini_publikasi as $item)
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
									<a href="{{route('berita-fe', $item->slug)}}" class="berita-terkini-judul-romadan">{{$item->judul}}</a>
								</div>



								
							</div>
						</div>

						
						
					</div>
					
					@empty
							<div class="container">                         
						<h5 class="romadan-faq m-t-2">
							Warta Terkini Kosong !
						</h5>
							  </div>
						
					@endforelse
					<div class="col-md-12 p-t-20 romadan-berita-button-home">
						<a href="{{route('publikasi-index-warta-fe')}}" class="btn-tentang-romadan flex-c-m size1 txt3-romadan trans-0-4 mt-3">
								Lihat Selengkapnya<i class="ml-3 fa fa-arrow-right"
								aria-hidden="true"></i>
							</a>
					</div>
					

				</div>
		</div>
	</section>
    {{-- ARTIKEL --}}
    <section class="section-welcome p-b-105" style="background-color: white;">
		<div class="container">
				<div class="col-lg-12">
					<div class="wrap-pic-welcome size2 bo-rad-10 hov-img-zoom m-l-r-auto">
						<div class="publikasi-home-warta">Artikel</div>
					</div>
				</div>
                <div class="col-lg-12">
					<div class="row">
					@forelse ($artikel_terkini_publikasi as $item)
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
									<a href="{{route('publikasi-index-artikel-fe', $item->slug)}}" class="berita-terkini-judul-romadan">{{$item->judul}}</a>
								</div>



								
							</div>
						</div>

						
						
					</div>
					
					@empty
							<div class="container">                         
						<h5 class="romadan-faq text-center m-t-2">
							Artikel Terkini Kosong !
						</h5>
							  </div>
						
					@endforelse
					<div class="col-md-12 p-t-20 romadan-berita-button-home">
						<a href="{{route('publikasi-index-artikel-fe')}}" class="btn-tentang-romadan flex-c-m size1 txt3-romadan trans-0-4 mt-3">
								Lihat Selengkapnya<i class="ml-3 fa fa-arrow-right"
								aria-hidden="true"></i>
							</a>
					</div>
					

				</div>
		</div>
	</section>
    


@section('script_fe')
@endsection

@endsection