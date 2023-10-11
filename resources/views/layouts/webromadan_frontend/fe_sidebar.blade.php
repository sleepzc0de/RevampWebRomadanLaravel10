
	<aside class="sidebar trans-0-4">
		<!-- Button Hide sidebar -->

		<div class="">
		<button class="btn-hide-sidebar color0-hov trans-0-4 mb-5"><i class="fa-solid fa-xmark"></i></button>
		<img src="{{asset('frontend_romadan_web/images/icons/romadan/logo_3.png')}}" alt="romadan_logo" width="280px" class="mt-4 pt-5 pl-3">
		</div>

		<!-- - -->
		<ul class="menu-sidebar p-t-20 p-b-70">
			<li class="t-center m-b-13">
				<a href="{{route('homefe')}}" class="txt19">Home</a>
			</li>
			<hr>
			<li class="t-center m-b-13">
				{{-- <a href="menu.html" class="txt19">Profile</a> --}}
				<div class="btn-group">
					{{-- <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
						Action
					</button> --}}
					<a class="txt19" data-toggle="dropdown" aria-expanded="false">Profile</a>
					<div class="dropdown-menu">
						<a class="dropdown-item" href="{{route('visi-misi-fe')}}">Visi Misi</a>
						<a class="dropdown-item" href="{{route('sejarah-fe')}}">Sejarah</a>
						<a class="dropdown-item" href="{{route('organisasi-fe')}}">Organisasi</a>
						<a class="dropdown-item" href="{{route('tentang-fe')}}">Tentang</a>
				</div>
			</li>
			
			<hr>
			<li class="t-center m-b-13">
				<a href="{{route('layanan-fe')}}" class="txt19">Layanan</a>
			</li>
			<hr>
			<li class="t-center m-b-13">
				<a href="{{route('kegiatan-index-fe')}}" class="txt19">Kegiatan</a>
			</li>
			<hr>
			<li class="t-center m-b-13">
				<a href="{{route('informasi-publik-index-fe')}}" class="txt19">Informasi Publik</a>
			</li>
			<hr>
			<li class="t-center m-b-13">
				<a href="{{route('publikasi-index-fe')}}" class="txt19">Publikasi</a>
			</li>
			<hr>
			<li class="t-center m-b-13">
				<a href="{{route('faq-index-fe')}}" class="txt19">FAQ</a>
			</li>
			<hr>
			{{-- <li class="t-center m-b-13">
				<a href="contact.html" class="txt19">Search</a>
			</li>	
			<hr> --}}
			<li class="t-center">
				<!-- Button3 -->
				<a href="reservation.html" class="btn3 flex-c-m size13 txt11 trans-0-4 m-l-r-auto">
					Kontak Kami
				</a>
			</li>

			
		</ul>
	</aside>