<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
    <script>
        let idleTime = 0;
        const idleInterval = setInterval(timerIncrement, 60000); // Check setiap 1 menit

        function timerIncrement() {
            idleTime = idleTime + 1;
            if (idleTime >= {{ config('session.idle_timeout', 15) }}) {
                window.location.href = '{{ route("login") }}';
            }
        }

        // Reset timer pada aktivitas user
        function resetTimer() {
            idleTime = 0;
        }

        // Event listeners untuk reset timer
        // document.addEventListener('mousemove', resetTimer);
        // document.addEventListener('keypress', resetTimer);
        // document.addEventListener('scroll', resetTimer);
        document.addEventListener('click', resetTimer);
        </script>

    {{-- Ubah <img data-blob-src="..."> jadi blob URL lewat fetch ter-otentikasi,
         supaya path gambar tidak terlihat langsung di HTML source backend.
         Dipasang dengan MutationObserver supaya gambar yang muncul belakangan
         (baris DataTable hasil AJAX) ikut terkonversi juga. --}}
    <script>
        (function () {
            const cache = new Map();

            async function loadBlobImage(img) {
                const src = img.dataset.blobSrc;
                if (!src || img.dataset.blobLoaded) return;
                img.dataset.blobLoaded = '1';

                try {
                    if (!cache.has(src)) {
                        const res = await fetch(src, { credentials: 'same-origin' });
                        if (!res.ok) throw new Error('blob fetch failed');
                        cache.set(src, URL.createObjectURL(await res.blob()));
                    }
                    img.src = cache.get(src);
                } catch (e) {
                    img.dataset.blobLoaded = '';
                }
            }

            function scan(root) {
                root.querySelectorAll('img[data-blob-src]').forEach(loadBlobImage);
            }

            document.addEventListener('DOMContentLoaded', function () {
                scan(document);

                new MutationObserver(function (mutations) {
                    mutations.forEach(function (m) {
                        m.addedNodes.forEach(function (node) {
                            if (node.nodeType !== 1) return;
                            if (node.matches && node.matches('img[data-blob-src]')) loadBlobImage(node);
                            if (node.querySelectorAll) scan(node);
                        });
                    });
                }).observe(document.body, { childList: true, subtree: true });
            });
        })();
    </script>
    @stack('scripts')



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





    @include('layouts.webromadan_backend.config')


</body>

</html>
