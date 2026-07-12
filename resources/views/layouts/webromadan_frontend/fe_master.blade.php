<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#052548">
    <meta name="robots" content="index, follow">
    <meta name="description" content="@yield('meta_description', 'Website resmi Biro Manajemen BMN dan Pengadaan, Kementerian Keuangan Republik Indonesia.')">
    <meta name="author" content="Kementerian Keuangan Republik Indonesia">

    <link rel="canonical" href="@yield('canonical_url', url()->current())">

    {{-- Open Graph (pratinjau saat dibagikan di WhatsApp/Facebook/dll.) --}}
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:site_name" content="Biro Manajemen BMN dan Pengadaan">
    <meta property="og:title" content="@yield('title', 'Biro Manajemen BMN dan Pengadaan')">
    <meta property="og:description" content="@yield('meta_description', 'Website resmi Biro Manajemen BMN dan Pengadaan, Kementerian Keuangan Republik Indonesia.')">
    <meta property="og:url" content="@yield('canonical_url', url()->current())">
    <meta property="og:image" content="@yield('meta_image', asset('frontend_romadan_web/images/icons/romadan/logo_3.png'))">
    <meta property="og:locale" content="id_ID">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', 'Biro Manajemen BMN dan Pengadaan')">
    <meta name="twitter:description" content="@yield('meta_description', 'Website resmi Biro Manajemen BMN dan Pengadaan, Kementerian Keuangan Republik Indonesia.')">
    <meta name="twitter:image" content="@yield('meta_image', asset('frontend_romadan_web/images/icons/romadan/logo_3.png'))">

    {{-- Terapkan tema gelap/terang sebelum CSS dimuat, agar tidak ada flash
         warna salah saat halaman pertama kali render (FOUC). --}}
    <script>
        (function () {
            var stored = localStorage.getItem('theme');
            var isDark = stored ? stored === 'dark' : window.matchMedia('(prefers-color-scheme: dark)').matches;
            document.documentElement.classList.toggle('dark', isDark);
        })();
    </script>

    <title>@yield('title', 'Biro Manajemen BMN dan Pengadaan')</title>

    <link rel="icon" type="image/png" href="{{ asset('frontend_romadan_web/images/icons/romadanlogo.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
          integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
          crossorigin="anonymous" referrerpolicy="no-referrer">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-white text-slate-700 dark:bg-navy-950 dark:text-slate-300">
    @include('layouts.webromadan_frontend.fe_header')

    <main>
        @yield('content')
    </main>

    @include('layouts.webromadan_frontend.fe_footer')
    @include('layouts.webromadan_frontend.fe_backtotop')

    @stack('scripts')
</body>
</html>
