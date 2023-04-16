
	<section class="section-intro">

		<div class="content-intro p-t-77 p-b-133" style="background-color: #EBF1FF;">
			<div class="container">
				<div class="title-section-ourmenu t-center m-b-22">
					<h5 class="romadan-berita m-t-2">
						Berita Terkini
					</h5>
				</div>
				<div class="row">
					@forelse ($berita_terkini as $item)
						<div class="col-md-4 p-t-30">
						<!-- Block1 -->
						<div class="blo4">
							<div class="pic-blo4 hov-img-zoom bo-rad-10 pos-relative">
								<a href="{{asset('storage/romadan_gambar_web/'.$item->image)}}">
									<img src="{{asset('storage/romadan_gambar_web/'.$item->image)}}" alt="IMG-BLOG">
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
				<div class="title-section-ourmenu t-center m-b-22">
					<h5 class="romadan-berita-kosong m-t-2">
						Berita Terkini Kosong !
					</h5>
				</div>
					</div>
						
					@endforelse
					{{-- <div class="col-md-4 p-t-30">
						<!-- Block1 -->
						<div class="blo1">
							<div class="wrap-pic-blo1 bo-rad-10 hov-img-zoom">
								<a href="#"><img src="{{asset('frontend_romadan_web/images/intro-01.jpg')}}" alt="IMG-INTRO"></a>
							</div>
							
							<div class="wrap-text-blo1 p-t-20">
								<h6>04 April 2023</h6>
								<br>
								<a href="#"><h4 class="txt5 color0-hov trans-0-4 m-b-13">
									Romantic Restaurant
								</h4></a>

								<p class="m-b-20">
									Phasellus lorem enim, luctus ut velit eget, con-vallis egestas eros.
								</p>

								
							</div>
						</div>
					</div>

					<div class="col-md-4 p-t-30">
						<!-- Block1 -->
						<div class="blo1">
							<div class="wrap-pic-blo1 bo-rad-10 hov-img-zoom">
								<a href=""><img src="{{asset('frontend_romadan_web/images/intro-02.jpg')}}" alt="IMG-INTRO"></a>
							</div>

							<div class="wrap-text-blo1 p-t-20">
								<h6>04 April 2023</h6>
								<br>
								<a href=""><h4 class="txt5 color0-hov trans-0-4 m-b-13">
									Delicious Food
								</h4></a>

								<p class="m-b-20">
									Aliquam eget aliquam magna, quis posuere risus ac justo ipsum nibh urna
								</p>

								
							</div>
						</div>
					</div>

					<div class="col-md-4 p-t-30">
						<!-- Block1 -->
						<div class="blo1">
							<div class="wrap-pic-blo1 bo-rad-10 hov-img-zoom">
								<a href="#"><img src="{{asset('frontend_romadan_web/images/intro-04.jpg')}}" alt="IMG-INTRO"></a>
							</div>

							<div class="wrap-text-blo1 p-t-20">
								<h6>04 April 2023</h6>
								<br>
								<a href="#"><h4 class="txt5 color0-hov trans-0-4 m-b-13">
									Red Wines You Love
								</h4></a>

								<p class="m-b-20">
									Sed ornare ligula eget tortor tempor, quis porta tellus dictum.
								</p>

								
							</div>
						</div>
					</div> --}}

				</div>
			</div>
		</div>
	</section>