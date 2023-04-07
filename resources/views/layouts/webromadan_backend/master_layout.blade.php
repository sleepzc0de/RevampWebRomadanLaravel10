<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>CMS - Biro Manajemen BMN dan Pengadaan</title>

    <!-- Global stylesheets -->
    <link href="{{ asset('webromadan/be/fonts/inter/inter.css')}}" rel="stylesheet" type="text/css">
    <link href="{{ asset('webromadan/be/icons/phosphor/styles.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{ asset('webromadan/fe/css/ltr/all.min.css')}}" id="stylesheet" rel="stylesheet" type="text/css">
    @yield('css')
    <link rel="icon" type="image/png" href="{{asset('frontend_romadan_web/images/icons/romadanlogo.png')}}"/>
    <!-- /global stylesheets -->

    <!-- Core JS files -->
    <script src="{{ asset('webromadan/be/demo/demo_configurator.js')}}"></script>
    <script src="{{ asset('webromadan/be/js/bootstrap/bootstrap.bundle.min.js')}}"></script>
    <!-- /core JS files -->

    <!-- Theme JS files -->
    @yield('script_atas')
    <script src="{{ asset('webromadan/fe/js/app.js')}}"></script>
    @yield('script_bawah')



    <!-- /theme JS files -->

</head>

<body>
    @include('layouts.webromadan_backend.navbar')


    <!-- Page content -->
    <div class="page-content">

        @include('layouts.webromadan_backend.sidebar')


        <!-- Main content -->
        <div class="content-wrapper">

            <!-- Inner content -->
            <div class="content-inner">

                  {{-- @include('layouts.webromadan_backend.page_header') --}}

                <!-- Content area -->
                <div class="content">
                    @yield('content')
                </div>
                <!-- /content area -->

                @include('layouts.webromadan_backend.footer')

            </div>
            <!-- /inner content -->

        </div>
        <!-- /main content -->

    </div>
    <!-- /page content -->





    @include('layouts.BACKUP.config')


</body>

</html>