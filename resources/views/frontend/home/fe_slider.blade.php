
	<section class="section-slide">
		<div class="wrap-slick1">
			<div class="slick1">

				{{-- SLIDER --}}
				

				@forelse ($berita_terkini as $item)
				<div class="item-slick1 item1-slick1" style="background-image: url({{asset('storage/romadan_gambar_web/'.$item->image)}});">
					
					<div class="wrap-content-slide1 sizefull flex-col-c-m p-l-15 p-r-15 p-t-150 p-b-170">
						<span class="caption1-slide1 romadan-judul-terkini t-center animated visible-false m-b-35" data-appear="fadeInDown">
							Berita Terkini
						</span>

						<h2 class="caption2-slide1 romadan-judul t-center animated visible-false m-b-50" data-appear="fadeInUp">
							{{$item->judul}}
						</h2>

						<div class="wrap-btn-slide1 animated visible-false" data-appear="zoomIn">
							<!-- Button1 -->
							<a href="{{route('berita-fe', $item->slug)}}" class="btn-romadan-title flex-c-m size-romadan1 txt3-romadan trans-0-4">
								Baca Selengkapnya
							</a>
						</div>
					</div>
				</div>
				@empty

				<div class="item-slick1 item1-slick1" style="background-color: black;">
					
					<div class="wrap-content-slide1 sizefull flex-col-c-m p-l-15 p-r-15 p-t-150 p-b-170">
						<span class="caption1-slide1 romadan-judul-terkini t-center animated visible-false m-b-35" data-appear="fadeInDown">
							Berita Terkini Belum Ada
						</span>

						<h2 class="caption2-slide1 romadan-judul t-center animated visible-false m-b-50" data-appear="fadeInUp">
							KOSONG
						</h2>

						<div class="wrap-btn-slide1 animated visible-false" data-appear="zoomIn">
							<!-- Button1 -->
							<a href="mailto:kemenkeu.prime@kemenkeu.go.id" class="btn-romadan-title flex-c-m size-romadan1 txt3-romadan trans-0-4">
								Hubungi Admin
							</a>
						</div>
					</div>
				</div>
					
				@endforelse

				{{-- <div class="item-slick1 item2-slick1" style="background-image: url({{asset('frontend_romadan_web/images/master-slides-02.jpg')}});">
					<div class="wrap-content-slide1 sizefull flex-col-c-m p-l-15 p-r-15 p-t-150 p-b-170">
						<span class="caption1-slide1 txt1 t-center animated visible-false m-b-15" data-appear="rollIn">
							Welcome to
						</span>

						<h2 class="caption2-slide1 tit1 t-center animated visible-false m-b-37" data-appear="lightSpeedIn">
							Pato Place
						</h2>

						<div class="wrap-btn-slide1 animated visible-false" data-appear="slideInUp">
							<!-- Button1 -->
							<a href="menu.html" class="btn1 flex-c-m size1 txt3 trans-0-4">
								Look Menu
							</a>
						</div>
					</div>
				</div>

				<div class="item-slick1 item3-slick1" style="background-image: url({{asset('frontend_romadan_web/images/master-slides-01.jpg')}});">
					<div class="wrap-content-slide1 sizefull flex-col-c-m p-l-15 p-r-15 p-t-150 p-b-170">
						<span class="caption1-slide1 txt1 t-center animated visible-false m-b-15" data-appear="rotateInDownLeft">
							Welcome to
						</span>

						<h2 class="caption2-slide1 tit1 t-center animated visible-false m-b-37" data-appear="rotateInUpRight">
							Pato Place
						</h2>

						<div class="wrap-btn-slide1 animated visible-false" data-appear="rotateIn">
							<!-- Button1 -->
							<a href="menu.html" class="btn1 flex-c-m size1 txt3 trans-0-4">
								Look Menu
							</a>
						</div>
					</div>
				</div> --}}
				{{-- END SLIDER --}}

			</div>

			<div class="wrap-slick1-dots"></div>
		</div>
	</section>