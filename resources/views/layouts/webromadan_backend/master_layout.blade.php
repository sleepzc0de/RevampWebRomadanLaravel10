<!DOCTYPE html>
<html lang="id" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="ck-upload-url" content="{{ route('media.ck-upload') }}">
    <title>CMS Romadan V.2 — Biro Manajemen BMN dan Pengadaan</title>
    <link rel="icon" type="image/png" href="{{ asset('frontend_romadan_web/images/icons/romadanlogo.png') }}"/>

    {{-- Terapkan tema tersimpan SEBELUM paint pertama supaya tidak flash --}}
    <script>
        if (localStorage.getItem('cms-theme') === 'dark') document.documentElement.classList.add('dark');
    </script>

    <link href="{{ asset('webromadan/be/fonts/inter/inter.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('webromadan/be/icons/phosphor/styles.min.css') }}" rel="stylesheet" type="text/css">
    @vite(['resources/css/backend.css', 'resources/js/backend.js'])
    @yield('css')

    {{-- Vendor JS per-halaman (jQuery/DataTables/dll) — dimuat di head seperti
         layout lama karena skrip inline tiap view mengandalkannya --}}
    @yield('script_atas')
    @yield('script_bawah')

    <script>
        let idleTime = 0;
        const idleInterval = setInterval(timerIncrement, 60000); // Check setiap 1 menit

        function timerIncrement() {
            idleTime = idleTime + 1;
            if (idleTime >= {{ config('session.idle_timeout', 15) }}) {
                window.location.href = '{{ route('login') }}';
            }
        }

        function resetTimer() {
            idleTime = 0;
        }

        document.addEventListener('click', resetTimer);
    </script>
    @stack('scripts')
</head>

<body class="min-h-screen">
    <div class="flex min-h-screen">

        {{-- ===== Sidebar (desktop tetap, mobile drawer via Alpine) ===== --}}
        <div x-data x-cloak x-show="$store.cms.sidebarOpen" x-transition.opacity
             class="fixed inset-0 z-40 bg-navy-950/70 lg:hidden"
             @click="$store.cms.sidebarOpen = false"></div>

        <aside x-data
               class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col bg-navy-900 transition-all duration-200 lg:translate-x-0"
               :class="[
                   $store.cms.sidebarOpen ? 'translate-x-0' : '-translate-x-full',
                   $store.cms.isRailCollapsed ? 'lg:w-20' : 'lg:w-72',
               ]">
            @include('layouts.webromadan_backend.sidebar')
        </aside>

        {{-- ===== Area utama ===== --}}
        <div x-data class="flex min-w-0 flex-1 flex-col transition-all duration-200" :class="$store.cms.isRailCollapsed ? 'lg:pl-20' : 'lg:pl-72'">

            @include('layouts.webromadan_backend.navbar')

            <main class="flex-1 px-4 py-6 sm:px-6 lg:px-8">
                @yield('content')
            </main>

            @include('layouts.webromadan_backend.footer')
        </div>
    </div>
</body>

</html>
