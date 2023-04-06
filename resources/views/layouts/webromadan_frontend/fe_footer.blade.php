
	<footer class="footer1-romadan-bg">
		<div class="container p-t-50 p-b-50">
			<div class="row">
				<div class="col-lg-6 footer1-ikuti-kami">
					Ikuti kami di media sosial
				</div>

				<div class="col-lg-6 footer1-medsos mt-3">
					{{-- MEDSOS --}}
					@forelse ($medsos as $item)

					<i class="{{$item->logo_medsos}} mr-2 ml-2" aria-hidden="true"></i><a href="{{$item->link_medsos}}">{{$item->nama_medsos}}</a>
						
					@empty

					<div class="col-lg-6 footer1-medsos">
					Data Medsos Belum Ada !
					</div>
						
					@endforelse
					
					{{-- END MEDSOS --}}
				</div>
			</div>
		</div>

		<div class="end-footer footer2-romadan-bg">
			<div class="container">
				<!-- <div class="flex-sb-m flex-w p-t-22 p-b-22"> -->
					<div class="row" style="text-align: center;">
						<div class="col-lg-6">
									<div class="mt-5">
										<img src="{{asset('frontend_romadan_web/images/icons/romadan/logo_4.png')}}" alt="ROMADAN-LOGO" width="400" class="mt-5">
									</div>
						</div>
							<div class="col-lg-6 footer2-hubungi-kami" style="text-align: center;">
								<div class="mt-5" style="text-align: justify;">
									<h9>Hubungi Kami</h9>
									<br>
									<h10><i class="fa-regular fa-envelope mr-2 mt-4"aria-hidden="true"></i></i>romadan@kemenkeu.go.id</h10>
									<br>
									<h10><i class="fa fa-phone mr-2 mt-3" aria-hidden="true"></i>081-2311-2345-678</h10>
									<br>
									<h10><i class="fa-regular fa-building mr-2 mt-3"></i>Gedung Djuanda 2 Lt. 15-17,</h10><br>
									<h10 class="ml-3">&nbsp;Jl. Dr. Wahidin Raya No. 1</h10>

									{{-- <h10>Gedung Djuanda 2 Lt. 15-17
									<br><h10 class="ml-3">&nbsp;Jl. Dr. Wahidin Raya No. 1</h10></h10> --}}
									
								</div>
							</div>
					</div>
					<div class="row">
						<br>
					</div>
					
					<!-- <div class="txt17 p-r-20 p-t-5 p-b-5">
						Copyright &copy; 2018 All rights reserved  |  This template is made with <i class="fa fa-heart"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a>
					</div> -->
				<!-- </div> -->
			</div>
		</div>
	</footer>