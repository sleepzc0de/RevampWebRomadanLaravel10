
	<section class="section-welcome p-t-120 p-b-105" style="background-color: white;">
		<div class="container">
			<div class="row">
				@forelse ($tentang as $item)
				<div class="col-md-6 p-t-45 p-b-30">
					<div class="wrap-text-welcome">
						<h6 class="txt-tentang-romadan t-center m-b-35 m-t-5" style="text-align: justify;">
							{{$item->judul}}
						</h6>

						<div class="t-center m-b-22 size3 " style="text-align: justify;">
							{!!$item->tentang!!}

							<a href="menu.html" class="btn-tentang-romadan flex-c-m size1 txt3-romadan trans-0-4 mt-3">
								Baca Profil Kami<i class="ml-3 fa fa-arrow-right"
								aria-hidden="true"></i>
							</a>
						</div>
						
											
					</div>
				</div>

				<div class="col-md-6 p-b-30">
					<div class="wrap-pic-welcome size2 bo-rad-10 hov-img-zoom m-l-r-auto">
						<a href="{{asset('storage/romadan_gambar_web/' . $item->image)}}"><img src="{{asset('storage/romadan_gambar_web/' . $item->image)}}" alt="IMG-OUR"></a>
					</div>
				</div>

				@empty
							
				@endforelse
			</div>
		</div>
	</section>