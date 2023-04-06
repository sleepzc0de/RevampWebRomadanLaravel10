<!DOCTYPE html>
<html lang="en">
<head>
	<title>Biro Manajemen BMN dan Pengadaan</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->
	<link rel="icon" type="image/png" href="{{asset('frontend_romadan_web/images/icons/romadanlogo.png')}}"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('frontend_romadan_web/vendor/bootstrap/css/bootstrap.min.css')}}">
<!--===============================================================================================-->
	{{-- <link rel="stylesheet" type="text/css" href="{{asset('frontend_romadan_web/fonts/font-awesome-4.7.0/css/font-awesome.min.css')}}"> --}}
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
     @include('layouts.webromadan_frontend.fe_header')

	<!-- Sidebar -->
    @include('layouts.webromadan_frontend.fe_sidebar')

	<!-- Slide1 -->
    @include('layouts.webromadan_frontend.fe_slider')

	<!-- Welcome -->
    @include('layouts.webromadan_frontend.fe_welcome')

	<!-- Berita -->
    @include('layouts.webromadan_frontend.fe_berita')

	<!-- Kumpulan Peraturan -->
    @include('layouts.webromadan_frontend.fe_kumpulperaturan')
	
	<!-- Footer -->
    @include('layouts.webromadan_frontend.fe_footer')

	<!-- Back to top -->
    @include('layouts.webromadan_frontend.fe_backtotop')


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
