<!DOCTYPE html>
<html lang="en">
<head>
	<title>Home</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->
	<link rel="icon" type="image/png" href="{{asset('frontend_romadan_web/images/icons/favicon.png')}}"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('frontend_romadan_web/vendor/bootstrap/css/bootstrap.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('frontend_romadan_web/fonts/font-awesome-4.7.0/css/font-awesome.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('frontend_romadan_web/fonts/themify/themify-icons.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('frontend_romadan_web/vendor/animate/animate.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('frontend_romadan_web/vendor/css-hamburgers/hamburgers.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('frontend_romadan_web/vendor/animsition/css/animsition.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('frontend_romadan_web/vendor/select2/select2.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('frontend_romadan_web/vendor/daterangepicker/daterangepicker.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('frontend_romadan_web/vendor/slick/slick.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('frontend_romadan_web/vendor/lightbox2/css/lightbox.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('frontend_romadan_web/css/util.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('frontend_romadan_web/css/main.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('frontend_romadan_web/css/romadan.css')}}">
<!--===============================================================================================-->
</head>
<body class="animsition">

	<!-- Header -->
	<header>
		<!-- Header desktop -->
		<div class="wrap-menu-header gradient1 trans-0-4">
			<div class="container h-full">
				<div class="wrap_header trans-0-3">
					<!-- Logo -->
					<div class="logo">
						<a href="index.html">
							<img src="{{asset('frontend_romadan_web/images/icons/romadan/logo_4.png')}}" alt="ROMADAN-LOGO" data-logofixed="{{asset('frontend_romadan_web/images/icons/romadan/logo_3.png')}}">
						</a>
					</div>

					<!-- Menu -->
					<div class="wrap_menu p-l-45 p-l-0-xl">
						<nav class="menu">
							<ul class="main_menu">
								<li class="dropdown">
									<a href="#" class="" data-toggle="dropdown">Profile </span></a>
									<ul class="dropdown-menu p-l-20 p-l-0-xl mt-4 dropdown-menu-romadan">
									{{-- MENU PROFILE --}}
									<li><a href="#">Visi Misi</a></li>
									<li><a href="#">Sejarah</a></li>
									<li><a href="#">Organisasi</a></li>
									{{-- END MENU PROFILE --}}
									</ul>
								</li>
								<li>
									<a href="menu.html">Layanan</a>
								</li>
								<li class="dropdown">
									<a href="#" class="" data-toggle="dropdown">Kegiatan </span></a>
									<ul class="dropdown-menu p-l-20 p-l-0-xl mt-4 dropdown-menu-romadan">
									<li><a href="#">Kegiatan 1</a></li>
									<li class="dropdown-submenu-romadan">
									<a href="#">Kegiatan 2 </a>
									<ul class="dropdown-menu p-l-20 p-l-0-xl">
										<li><a href="#">Sub-kegiatan 2.1</a></li>
										<li><a href="#">Sub-kegiatan 2.2</a></li>
										<li class="dropdown-submenu-romadan">
										<a href="#">Sub-kegiatan 2.3 </a>
										<ul class="dropdown-menu p-l-20 p-l-0-xl">
											<li><a href="#">Sub-sub-kegiatan 2.3.1</a></li>
											<li><a href="#">Sub-sub-kegiatan 2.3.2</a></li>
										</ul>
										</li>
									</ul>
									</li>
									<li><a href="#">Kegiatan 3</a></li>
									</ul>
								</li>
								<li>
									<a href="menu.html">Informasi Publik</a>
								</li>
								<li class="dropdown">
									<a href="#" class="" data-toggle="dropdown">Publikasi </span></a>
									<ul class="dropdown-menu p-l-20 p-l-0-xl mt-4 dropdown-menu-romadan">
									{{-- MENU PROFILE --}}
									<li><a href="#">Berita</a></li>
									<li><a href="#">Warta</a></li>
									<li><a href="#">Artikel</a></li>
									{{-- END MENU PROFILE --}}
									</ul>
								</li>
								<li>
									<a href="menu.html">FAQ</a>
								</li>
								<li>
									<a href="menu.html">Search</a>
								</li>
							</ul>
							
						</nav>
					</div>

					<!-- Social -->
					<div class="social flex-w flex-l-m p-r-10">
						
						<button class="btn-show-sidebar romadan-menu-mobile m-l-33 trans-0-4"></button>
					</div>
					
				</div>
			</div>
		</div>
	</header>

	<!-- Sidebar -->
	<aside class="sidebar trans-0-4">
		<!-- Button Hide sidebar -->
		<button class="btn-hide-sidebar ti-close color0-hov trans-0-4"></button>

		<!-- - -->
		<ul class="menu-sidebar p-t-95 p-b-70">
			<li class="t-center m-b-13">
				<a href="index.html" class="txt19">Home</a>
			</li>

			<li class="t-center m-b-13">
				<a href="menu.html" class="txt19">Menu</a>
			</li>

			<li class="t-center m-b-13">
				<a href="gallery.html" class="txt19">Gallery</a>
			</li>

			<li class="t-center m-b-13">
				<a href="about.html" class="txt19">About</a>
			</li>

			<li class="t-center m-b-13">
				<a href="blog.html" class="txt19">Blog</a>
			</li>

			<li class="t-center m-b-33">
				<a href="contact.html" class="txt19">Contact</a>
			</li>

			<li class="t-center">
				<!-- Button3 -->
				<a href="reservation.html" class="btn3 flex-c-m size13 txt11 trans-0-4 m-l-r-auto">
					Reservation
				</a>
			</li>
		</ul>

		<!-- - -->
		<div class="gallery-sidebar t-center p-l-60 p-r-60 p-b-40">
			<!-- - -->
			<h4 class="txt20 m-b-33">
				Gallery
			</h4>

			<!-- Gallery -->
			<div class="wrap-gallery-sidebar flex-w">
				<a class="item-gallery-sidebar wrap-pic-w" href="images/photo-gallery-01.jpg" data-lightbox="gallery-footer">
					<img src="{{asset('frontend_romadan_web/images/photo-gallery-thumb-01.jpg')}}" alt="GALLERY">
				</a>

				<a class="item-gallery-sidebar wrap-pic-w" href="images/photo-gallery-02.jpg" data-lightbox="gallery-footer">
					<img src="{{asset('frontend_romadan_web/images/photo-gallery-thumb-02.jpg')}}" alt="GALLERY">
				</a>

				<a class="item-gallery-sidebar wrap-pic-w" href="images/photo-gallery-03.jpg" data-lightbox="gallery-footer">
					<img src="{{asset('frontend_romadan_web/images/photo-gallery-thumb-03.jpg')}}" alt="GALLERY">
				</a>

				<a class="item-gallery-sidebar wrap-pic-w" href="images/photo-gallery-05.jpg" data-lightbox="gallery-footer">
					<img src="{{asset('frontend_romadan_web/images/photo-gallery-thumb-05.jpg')}}" alt="GALLERY">
				</a>

				<a class="item-gallery-sidebar wrap-pic-w" href="images/photo-gallery-06.jpg" data-lightbox="gallery-footer">
					<img src="{{asset('frontend_romadan_web/images/photo-gallery-thumb-06.jpg')}}" alt="GALLERY">
				</a>

				<a class="item-gallery-sidebar wrap-pic-w" href="images/photo-gallery-07.jpg" data-lightbox="gallery-footer">
					<img src="{{asset('frontend_romadan_web/images/photo-gallery-thumb-07.jpg')}}" alt="GALLERY">
				</a>

				<a class="item-gallery-sidebar wrap-pic-w" href="images/photo-gallery-09.jpg" data-lightbox="gallery-footer">
					<img src="{{asset('frontend_romadan_web/images/photo-gallery-thumb-09.jpg')}}" alt="GALLERY">
				</a>

				<a class="item-gallery-sidebar wrap-pic-w" href="images/photo-gallery-10.jpg" data-lightbox="gallery-footer">
					<img src="{{asset('frontend_romadan_web/images/photo-gallery-thumb-10.jpg')}}" alt="GALLERY">
				</a>

				<a class="item-gallery-sidebar wrap-pic-w" href="images/photo-gallery-11.jpg" data-lightbox="gallery-footer">
					<img src="{{asset('frontend_romadan_web/images/photo-gallery-thumb-11.jpg')}}" alt="GALLERY">
				</a>
			</div>
		</div>
	</aside>

	<!-- Slide1 -->
	<section class="section-slide">
		<div class="wrap-slick1">
			<div class="slick1">
				<div class="item-slick1 item1-slick1" style="background-image: url({{asset('frontend_romadan_web/images/slide1-01.jpg')}});">
					
					<div class="wrap-content-slide1 sizefull flex-col-c-m p-l-15 p-r-15 p-t-150 p-b-170">
						<span class="caption1-slide1 romadan-judul-terkini t-center animated visible-false m-b-35" data-appear="fadeInDown">
							Berita Terkini
						</span>

						<h2 class="caption2-slide1 romadan-judul t-center animated visible-false m-b-50" data-appear="fadeInUp">
							Es Buah Memang Segar!
						</h2>

						<div class="wrap-btn-slide1 animated visible-false" data-appear="zoomIn">
							<!-- Button1 -->
							<a href="menu.html" class="btn-romadan-title flex-c-m size-romadan1 txt3-romadan trans-0-4">
								Baca Selengkapnya
							</a>
						</div>
					</div>
				</div>

				<div class="item-slick1 item2-slick1" style="background-image: url({{asset('frontend_romadan_web/images/master-slides-02.jpg')}});">
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
				</div>

			</div>

			<div class="wrap-slick1-dots"></div>
		</div>
	</section>

	<!-- Welcome -->
	<section class="section-welcome p-t-120 p-b-105" style="background-color: white;">
		<div class="container">
			<div class="row">
				<div class="col-md-6 p-t-45 p-b-30">
					<div class="wrap-text-welcome t-center">

						
						<h6 class="tit3 t-center m-b-35 m-t-5">
							Selamat Datang !
						</h6>

						<p class="t-center m-b-22 size3 m-l-r-auto" style="text-align: justify;">
							Biro Manajemen BMN dan Pengadaan merupakan lorem ipsum dolor sit amet. Temukan pintu kemana saja di Doraemon.
						</p>

						<a href="about.html" class="txt4">
							Our Story
							<i class="fa fa-long-arrow-right m-l-10" aria-hidden="true"></i>
						</a>
					</div>
				</div>

				<div class="col-md-6 p-b-30">
					<div class="wrap-pic-welcome size2 bo-rad-10 hov-img-zoom m-l-r-auto">
						<img src="{{asset('frontend_romadan_web/images/our-story-01.jpg')}}" alt="IMG-OUR">
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- Intro -->
	<section class="section-intro">
		<!-- <div class="header-intro parallax100 t-center p-t-135 p-b-158" style="background-image: url(images/bg-intro-01.jpg);">
			<span class="tit2 p-l-15 p-r-15">
				Discover
			</span>

			<h3 class="tit4 t-center p-l-15 p-r-15 p-t-3">
				Pato Place
			</h3>
		</div> -->

		<div class="content-intro p-t-77 p-b-133" style="background-color: #EBF1FF;">
			<div class="container">
				<div class="title-section-ourmenu t-center m-b-22">
					<h5 class="romadan-berita m-t-2">
						Berita Terkini
					</h5>
				</div>
				<div class="row">
					<div class="col-md-4 p-t-30">
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
					</div>

				</div>
			</div>
		</div>
	</section>

	<!-- Our menu -->
	<section class="section-ourmenu p-t-115 p-b-120" style="background-color: white;">
		<div class="container">
			<div class="title-section-ourmenu t-center m-b-22">
				<div class="romadan-kumpulan-peraturan">
					Kumpulan Peraturan Tentang BMN & Pengadaan
				</div>
			</div>

			<div class="row p-t-65">
				<div class="blo1 col-md-4">
					<div class="card-body">
						<h5 class="card-title">Rumah Negara</h5>
						<p class="card-text">Kumpulan Peraturan Tentang Rumah Negara</p>
						<br>
						<a href="#" class="btn-romadan1">Lihat Selengkapnya <i class="ml-3 fa fa-arrow-right" aria-hidden="true"></i></a>
					</div>
				</div>

				<div class="blo1 col-md-4">
					<div class="card-body">
						<h5 class="card-title">Rumah Negara</h5>
						<p class="card-text">Kumpulan Peraturan Tentang Rumah Negara</p>
						<br>
						<a href="#" class="btn-romadan1">Lihat Selengkapnya <i class="ml-3 fa fa-arrow-right"
								aria-hidden="true"></i></a>
					</div>
				</div>

				<div class="blo1 col-md-4">
					<div class="card-body">
						<h5 class="card-title">Rumah Negara</h5>
						<p class="card-text">Kumpulan Peraturan Tentang Rumah Negara</p>
						<br>
						<a href="#" class="btn-romadan1">Lihat Selengkapnya <i class="ml-3 fa fa-arrow-right"
								aria-hidden="true"></i></a>
					</div>
				</div>


			</div>

		</div>
	</section>


	

	<!-- Footer -->
	<footer class="footer1-romadan-bg">
		<div class="container p-t-40 p-b-70">
			<div class="row">
				<div class="col-sm-6 col-md-4 p-t-50">
					<!-- - -->
					<h4 class="txt13 m-b-33">
						Contact Us
					</h4>

					<ul class="m-b-70">
						<li class="txt14 m-b-14">
							<i class="fa fa-map-marker fs-16 dis-inline-block size19" aria-hidden="true"></i>
							8th floor, 379 Hudson St, New York, NY 10018
						</li>

						<li class="txt14 m-b-14">
							<i class="fa fa-phone fs-16 dis-inline-block size19" aria-hidden="true"></i>
							(+1) 96 716 6879
						</li>

						<li class="txt14 m-b-14">
							<i class="fa fa-envelope fs-13 dis-inline-block size19" aria-hidden="true"></i>
							contact@site.com
						</li>
					</ul>

					<!-- - -->
					<h4 class="txt13 m-b-32">
						Opening Times
					</h4>

					<ul>
						<li class="txt14">
							09:30 AM – 11:00 PM
						</li>

						<li class="txt14">
							Every Day
						</li>
					</ul>
				</div>

				<div class="col-sm-6 col-md-4 p-t-50">
					<!-- - -->
					<h4 class="txt13 m-b-33">
						Latest twitter
					</h4>

					<div class="m-b-25">
						<span class="fs-13 color2 m-r-5">
							<i class="fa fa-twitter" aria-hidden="true"></i>
						</span>
						<a href="#" class="txt15">
							@colorlib
						</a>

						<p class="txt14 m-b-18">
							Activello is a good option. It has a slider built into that displays the featured image in the slider.
							<a href="#" class="txt15">
								https://buff.ly/2zaSfAQ
							</a>
						</p>

						<span class="txt16">
							21 Dec 2017
						</span>
					</div>

					<div>
						<span class="fs-13 color2 m-r-5">
							<i class="fa fa-twitter" aria-hidden="true"></i>
						</span>
						<a href="#" class="txt15">
							@colorlib
						</a>

						<p class="txt14 m-b-18">
							Activello is a good option. It has a slider built into that displays
							<a href="#" class="txt15">
								https://buff.ly/2zaSfAQ
							</a>
						</p>

						<span class="txt16">
							21 Dec 2017
						</span>
					</div>
				</div>

				<div class="col-sm-6 col-md-4 p-t-50">
					<!-- - -->
					<h4 class="txt13 m-b-38">
						Gallery
					</h4>

					<!-- Gallery footer -->
					<div class="wrap-gallery-footer flex-w">
						<a class="item-gallery-footer wrap-pic-w" href="images/photo-gallery-01.jpg" data-lightbox="gallery-footer">
							<img src="{{asset('frontend_romadan_web/images/photo-gallery-thumb-01.jpg')}}" alt="GALLERY">
						</a>

						<a class="item-gallery-footer wrap-pic-w" href="images/photo-gallery-02.jpg" data-lightbox="gallery-footer">
							<img src="{{asset('frontend_romadan_web/images/photo-gallery-thumb-02.jpg')}}" alt="GALLERY">
						</a>

						<a class="item-gallery-footer wrap-pic-w" href="images/photo-gallery-03.jpg" data-lightbox="gallery-footer">
							<img src="{{asset('frontend_romadan_web/images/photo-gallery-thumb-03.jpg')}}" alt="GALLERY">
						</a>

						<a class="item-gallery-footer wrap-pic-w" href="images/photo-gallery-04.jpg" data-lightbox="gallery-footer">
							<img src="{{asset('frontend_romadan_web/images/photo-gallery-thumb-04.jpg')}}" alt="GALLERY">
						</a>

						<a class="item-gallery-footer wrap-pic-w" href="images/photo-gallery-05.jpg" data-lightbox="gallery-footer">
							<img src="{{asset('frontend_romadan_web/images/photo-gallery-thumb-05.jpg')}}" alt="GALLERY">
						</a>

						<a class="item-gallery-footer wrap-pic-w" href="images/photo-gallery-06.jpg" data-lightbox="gallery-footer">
							<img src="{{asset('frontend_romadan_web/images/photo-gallery-thumb-06.jpg')}}" alt="GALLERY">
						</a>

						<a class="item-gallery-footer wrap-pic-w" href="images/photo-gallery-07.jpg" data-lightbox="gallery-footer">
							<img src="{{asset('frontend_romadan_web/images/photo-gallery-thumb-07.jpg')}}" alt="GALLERY">
						</a>

						<a class="item-gallery-footer wrap-pic-w" href="images/photo-gallery-08.jpg" data-lightbox="gallery-footer">
							<img src="{{asset('frontend_romadan_web/images/photo-gallery-thumb-08.jpg')}}" alt="GALLERY">
						</a>

						<a class="item-gallery-footer wrap-pic-w" href="images/photo-gallery-09.jpg" data-lightbox="gallery-footer">
							<img src="{{asset('frontend_romadan_web/images/photo-gallery-thumb-09.jpg')}}" alt="GALLERY">
						</a>

						<a class="item-gallery-footer wrap-pic-w" href="images/photo-gallery-10.jpg" data-lightbox="gallery-footer">
							<img src="{{asset('frontend_romadan_web/images/photo-gallery-thumb-10.jpg')}}" alt="GALLERY">
						</a>

						<a class="item-gallery-footer wrap-pic-w" href="images/photo-gallery-11.jpg" data-lightbox="gallery-footer">
							<img src="{{asset('frontend_romadan_web/images/photo-gallery-thumb-11.jpg')}}" alt="GALLERY">
						</a>

						<a class="item-gallery-footer wrap-pic-w" href="images/photo-gallery-12.jpg" data-lightbox="gallery-footer">
							<img src="{{asset('frontend_romadan_web/images/photo-gallery-thumb-12.jpg')}}" alt="GALLERY">
						</a>
					</div>

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
							<div class="col-lg-6 footer2-hubungi-kami" style="text-align: justify;">
								<div class="mt-5">
									<h9>Hubungi Kami</h9>
									<br>
									<h10><i class="fa fa-envelope-o mr-2 mt-4" aria-hidden="true"></i>romadan@kemenkeu.go.id</h10>
									<br>
									<h10><i class="fa fa-phone mr-2 mt-3" aria-hidden="true"></i>081-2311-2345-678</h10>
									<br>
									<h10><i class="fa fa-building-o mr-2 mt-3" aria-hidden="true"></i>Gedung Djuanda 2 Lt. 15-17
									<br><h10 class="ml-3">&nbsp;Jl. Dr. Wahidin Raya No. 1</h10></h10>
									
								</div>
							</div>
					</div>
					
					<!-- <div class="txt17 p-r-20 p-t-5 p-b-5">
						Copyright &copy; 2018 All rights reserved  |  This template is made with <i class="fa fa-heart"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a>
					</div> -->
				<!-- </div> -->
			</div>
		</div>
	</footer>


	<!-- Back to top -->
	<div class="btn-back-to-top bg0-hov" id="myBtn">
		<span class="symbol-btn-back-to-top">
			<i class="fa fa-angle-double-up" aria-hidden="true"></i>
		</span>
	</div>

	<!-- Container Selection1 -->
	<div id="dropDownSelect1"></div>

	<!-- Modal Video 01-->
	<div class="modal fade" id="modal-video-01" tabindex="-1" role="dialog" aria-hidden="true">

		<div class="modal-dialog" role="document" data-dismiss="modal">
			<div class="close-mo-video-01 trans-0-4" data-dismiss="modal" aria-label="Close">&times;</div>

			<div class="wrap-video-mo-01">
				<div class="w-full wrap-pic-w op-0-0"><img src="{{asset('frontend_romadan_web/images/icons/video-16-9.jpg')}}" alt="IMG"></div>
				<div class="video-mo-01">
					<iframe src="https://www.youtube.com/embed/5k1hSu2gdKE?rel=0&amp;showinfo=0" allowfullscreen></iframe>
				</div>
			</div>
		</div>
	</div>



<!--===============================================================================================-->
	<script type="text/javascript" src="{{asset('frontend_romadan_web/vendor/jquery/jquery-3.2.1.min.js')}}"></script>
<!--===============================================================================================-->
	<script type="text/javascript" src="{{asset('frontend_romadan_web/vendor/animsition/js/animsition.min.js')}}"></script>
<!--===============================================================================================-->
	<script type="text/javascript" src="{{asset('frontend_romadan_web/vendor/bootstrap/js/popper.js')}}"></script>
	<script type="text/javascript" src="{{asset('frontend_romadan_web/vendor/bootstrap/js/bootstrap.min.js')}}"></script>
<!--===============================================================================================-->
	<script type="text/javascript" src="{{asset('frontend_romadan_web/vendor/select2/select2.min.js')}}"></script>
<!--===============================================================================================-->
	<script type="text/javascript" src="{{asset('frontend_romadan_web/vendor/daterangepicker/moment.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('frontend_romadan_web/vendor/daterangepicker/daterangepicker.js')}}"></script>
<!--===============================================================================================-->
	<script type="text/javascript" src="{{asset('frontend_romadan_web/vendor/slick/slick.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('frontend_romadan_web/js/slick-custom.js')}}"></script>
<!--===============================================================================================-->
	<script type="text/javascript" src="{{asset('frontend_romadan_web/vendor/parallax100/parallax100.js')}}"></script>
	<script type="text/javascript">
        $('.parallax100').parallax100();
	</script>
<!--===============================================================================================-->
	<script type="text/javascript" src="{{asset('frontend_romadan_web/vendor/countdowntime/countdowntime.js')}}"></script>
<!--===============================================================================================-->
	<script type="text/javascript" src="{{asset('frontend_romadan_web/vendor/lightbox2/js/lightbox.min.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{asset('frontend_romadan_web/js/main.js')}}"></script>

</body>
</html>
