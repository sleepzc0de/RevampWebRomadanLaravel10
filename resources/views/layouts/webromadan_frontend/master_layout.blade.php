<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Biro Manajemen BMN dan Pengadaan</title>

    <!-- Global stylesheets -->
    <link href="{{ asset('webromadan/be/fonts/inter/inter.css')}}" rel="stylesheet" type="text/css">
    <link href="{{ asset('webromadan/be/icons/phosphor/styles.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{ asset('webromadan/fe/css/ltr/all.min.css')}}" id="stylesheet" rel="stylesheet" type="text/css">

    <link href="{{ asset('webromadan/be/icons/material/styles.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{ asset('webromadan/be/icons/fontawesome/styles.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('webromadan/be/css/animate.min.css')}}" rel="stylesheet" type="text/css">
    @yield('css')
    <!-- /global stylesheets -->

    <!-- Core JS files -->
    <script src="{{ asset('webromadan/be/demo/demo_configurator.js')}}">
    </script>
    <script src="{{asset('webromadan/be/js/bootstrap/bootstrap.bundle.min.js')}}"></script>
    <!-- /core JS files -->

    <!-- Theme JS files -->
    @yield('script_atas')
    <script src="{{asset('webromadan/be/js/vendor/media/glightbox.min.js')}}"></script>
    <script src="{{asset('webromadan/fe/js/app.js')}}"></script>
    <script src="{{asset('webromadan/be/demo/pages/animations_css3.js')}}"></script>
    <script src="{{asset('webromadan/be/demo/pages/content_cards_content.js')}}"></script>
    @yield('script_bawah')



    <!-- /theme JS files -->

</head>

<body background="#F5F5F5">
    

    {{-- @include('layouts.webromadan_frontend.navbar') --}}

    <!-- Page content -->
    <div class="page-content">


        <!-- Main content -->
        <div class="content-wrapper">

            <!-- Inner content -->
            <div class="content-inner">

                <!-- Content area -->
                {{-- <div class="content">  --}}
                @yield('content')
                {{-- </div> --}}
                <!-- /content area -->

                {{-- @include('layouts.webromadan_frontend.footer') --}}

            </div>
            <!-- /inner content -->

        </div>
        <!-- /main content -->

    </div>
    <!-- /page content -->





    {{-- @include('layouts.webromadan_frontend.config') --}}


</body>

</html>