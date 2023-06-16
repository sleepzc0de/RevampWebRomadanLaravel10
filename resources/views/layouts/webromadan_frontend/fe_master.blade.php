<!DOCTYPE html>
<html lang="en">
<head>
	<title>Biro Manajemen BMN dan Pengadaan</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->
	<link rel="icon" type="image/png" href="{{asset('frontend_romadan_web/images/icons/romadanlogo.png')}}"/>
<!--===============================================================================================-->
	{{-- <link rel="stylesheet" type="text/css" href="{{asset('frontend_romadan_web/vendor/bootstrap/css/bootstrap.min.css')}}"> --}}
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
<!--===============================================================================================-->
{{-- OLD FONT AWESOME --}}
	{{-- <link rel="stylesheet" type="text/css" href="{{asset('frontend_romadan_web/fonts/font-awesome-4.7.0/css/font-awesome.min.css')}}"> --}}
	{{-- NEW FONT AWESOME --}}
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<!--===============================================================================================-->
{{-- OLD THEMIFY --}}
	{{-- <link rel="stylesheet" type="text/css" href="{{asset('frontend_romadan_web/fonts/themify/themify-icons.css')}}"> --}}
	{{-- NEW THEMIFY --}}
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@icon/themify-icons@1.0.1-alpha.3/themify-icons.css">
<!--===============================================================================================-->
{{-- OLD ANIMATE --}}
	{{-- <link rel="stylesheet" type="text/css" href="{{asset('frontend_romadan_web/vendor/animate/animate.css')}}"> --}}
	{{-- NEW ANIMATE --}}
	 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.css" integrity="sha512-kHUKImNBR9mcwQ1u4RILtsiJmC6u539bgkRapfGrbwXbkPxfapkzAXvdGnrTu3blGNXHPCX0klrlE7zAZhr+jA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<!--===============================================================================================-->
{{-- OLD HAMBURGERS --}}
	{{-- <link rel="stylesheet" type="text/css" href="{{asset('frontend_romadan_web/vendor/css-hamburgers/hamburgers.min.css')}}"> --}}
	{{-- NEW HAMBURGERS --}}
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/hamburgers/1.2.1/hamburgers.min.css" integrity="sha512-+mlclc5Q/eHs49oIOCxnnENudJWuNqX5AogCiqRBgKnpoplPzETg2fkgBFVC6WYUVxYYljuxPNG8RE7yBy1K+g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<!--===============================================================================================-->
{{-- OLD ANIMSTION --}}
	{{-- <link rel="stylesheet" type="text/css" href="{{asset('frontend_romadan_web/vendor/animsition/css/animsition.min.css')}}"> --}}
	{{-- NEW ANIMSTION --}}
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animsition/4.0.0/css/animsition.min.css" integrity="sha512-PhW416ZWZBw30eOTHRTNX+Z/14dZVYx13glqBSSFeeLAQGZP7JUCzbWt//ZbB+iaGJ2ugphHN7fH+ybXtGPVVg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<!--===============================================================================================-->
{{-- OLD SELECT2 --}}
	{{-- <link rel="stylesheet" type="text/css" href="{{asset('frontend_romadan_web/vendor/select2/select2.min.css')}}"> --}}
	{{-- NEW SELECT2 --}}
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('frontend_romadan_web/vendor/daterangepicker/daterangepicker.css')}}">
<!--===============================================================================================-->
{{-- OLD SLICK --}}
	{{-- <link rel="stylesheet" type="text/css" href="{{asset('frontend_romadan_web/vendor/slick/slick.css')}}"> --}}
	{{-- NEW SLICK --}}
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.css" integrity="sha512-wR4oNhLBHf7smjy0K4oqzdWumd+r5/+6QO/vDda76MW5iug4PT7v86FoEkySIJft3XA0Ae6axhIvHrqwm793Nw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<!--===============================================================================================-->
{{-- OLD LIGHTBOX --}}
	{{-- <link rel="stylesheet" type="text/css" href="{{asset('frontend_romadan_web/vendor/lightbox2/css/lightbox.min.css')}}"> --}}
	{{-- NEW LIGHTBOX --}}
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animsition/4.0.0/css/animsition.min.css" integrity="sha512-PhW416ZWZBw30eOTHRTNX+Z/14dZVYx13glqBSSFeeLAQGZP7JUCzbWt//ZbB+iaGJ2ugphHN7fH+ybXtGPVVg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('frontend_romadan_web/css/util.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('frontend_romadan_web/css/main.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('frontend_romadan_web/css/romadan.css')}}">
<!--===============================================================================================-->
	@yield('css_fe')
</head>
<body class="animsition">

	<!-- Header -->
     @include('layouts.webromadan_frontend.fe_header')
	 

	 {{-- CONTENTS --}}
 	@include('layouts.webromadan_frontend.fe_sidebar')
	 @yield('content')

	{{-- END CONTENTS --}}
	
	<!-- Footer -->
    @include('layouts.webromadan_frontend.fe_footer')

	<!-- Back to top -->
    @include('layouts.webromadan_frontend.fe_backtotop')


<!--===============================================================================================-->
	{{--OLD JQUERY  --}}
{{-- <script type="text/javascript" src="{{asset('frontend_romadan_web/vendor/jquery/jquery-3.2.1.min.js')}}"></script> --}}
	{{-- NEW JQUERY  --}}
	<script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
<!--===============================================================================================-->
{{-- OLD ANIMSITION --}}
	{{-- <script type="text/javascript" src="{{asset('frontend_romadan_web/vendor/animsition/js/animsition.min.js')}}"></script> --}}
	{{-- NEW ANIMSTITION --}}
	<script src="https://cdnjs.cloudflare.com/ajax/libs/animsition/4.0.0/js/animsition.min.js" integrity="sha512-hPazG7s09qw5kT58cK6A0VOeXLyWG9snVMMWCXATNiqhOMlTiSGdLaF23nN5QisQF9nAq+z291SZJef+hhH8SQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	
<!--===============================================================================================-->
{{-- OLD BOOTSTRAP 4.00 --}}
	{{-- <script type="text/javascript" src="{{asset('frontend_romadan_web/vendor/bootstrap/js/popper.js')}}"></script>
	<script type="text/javascript" src="{{asset('frontend_romadan_web/vendor/bootstrap/js/bootstrap.min.js')}}"></script> --}}
	{{-- NEW BOOTSTRAP 4.6.X --}}
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>
<!--===============================================================================================-->
{{-- OLD SELECT2 --}}
	{{-- <script type="text/javascript" src="{{asset('frontend_romadan_web/vendor/select2/select2.min.js')}}"></script> --}}
	{{-- NEW SELECT2 --}}
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!--===============================================================================================-->
	<script type="text/javascript" src="{{asset('frontend_romadan_web/vendor/daterangepicker/moment.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('frontend_romadan_web/vendor/daterangepicker/daterangepicker.js')}}"></script>
<!--===============================================================================================-->
{{-- OLD SLICK --}}
	{{-- <script type="text/javascript" src="{{asset('frontend_romadan_web/vendor/slick/slick.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('frontend_romadan_web/js/slick-custom.js')}}"></script> --}}
	{{-- NEW SLICK --}}
	<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js" integrity="sha512-XtmMtDEcNz2j7ekrtHvOVR4iwwaD6o/FUJe6+Zq+HgcCsk3kj4uSQQR8weQ2QVj1o0Pk6PwYLohm206ZzNfubg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script type="text/javascript" src="{{asset('frontend_romadan_web/js/slick-custom.js')}}"></script>
	
<!--===============================================================================================-->
	<script type="text/javascript" src="{{asset('frontend_romadan_web/vendor/parallax100/parallax100.js')}}"></script>
	<script type="text/javascript">
        $('.parallax100').parallax100();
	</script>
<!--===============================================================================================-->
	<script type="text/javascript" src="{{asset('frontend_romadan_web/vendor/countdowntime/countdowntime.js')}}"></script>
<!--===============================================================================================-->
{{-- OLD LIGHTBOX --}}
	{{-- <script type="text/javascript" src="{{asset('frontend_romadan_web/vendor/lightbox2/js/lightbox.min.js')}}"></script> --}}
	{{-- NEW LIGHTBOX --}}
	<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js" integrity="sha512-Ixzuzfxv1EqafeQlTCufWfaC6ful6WFqIz4G+dWvK0beHw0NVJwvCKSgafpy5gwNqKmgUfIBraVwkKI+Cz0SEQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!--===============================================================================================-->
	<script src="{{asset('frontend_romadan_web/js/main.js')}}"></script>
	@yield('script_fe')

</body>
</html>
