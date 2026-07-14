@php
    $feNavProfile = [
        ['label' => 'Visi &amp; Misi', 'route' => 'visi-misi-fe'],
        ['label' => 'Sejarah', 'route' => 'sejarah-fe'],
        ['label' => 'Struktur Organisasi', 'route' => 'organisasi-fe'],
        ['label' => 'Tentang Kami', 'route' => 'tentang-fe'],
    ];
    $feNavMain = [
        ['label' => 'Layanan', 'route' => 'layanan-fe'],
        ['label' => 'Informasi Publik', 'route' => 'informasi-publik-index-fe'],
        ['label' => 'Publikasi', 'route' => 'publikasi-index-fe'],
        ['label' => 'FAQ', 'route' => 'faq-index-fe'],
    ];
@endphp

<header x-data="siteNavbar" class="sticky top-0 z-50 bg-white/95 backdrop-blur-sm transition-shadow duration-300 dark:bg-navy-950/95"
    :class="scrolled ? 'shadow-[0_1px_0_0_rgba(3,23,51,.06),0_8px_24px_-16px_rgba(3,23,51,.25)]' : 'shadow-[0_1px_0_0_rgba(3,23,51,.06)]'">
    <div class="fe-container flex h-18 items-center justify-between gap-4 py-2.5">
        {{-- Logo --}}
        <a href="{{ route('homefe') }}" class="flex min-w-0 items-center gap-3">
            <img src="{{ asset('frontend_romadan_web/images/icons/romadanlogo.png') }}" alt="Logo"
                class="h-10 w-10 flex-none object-contain sm:h-11 sm:w-11">
            <span class="min-w-0 leading-tight">
                <span class="block truncate text-[13px] font-bold text-navy-800 sm:text-[15px] dark:text-white">Biro Manajemen BMN &amp; Pengadaan</span>
                <span class="block truncate text-[11px] font-medium text-slate-500 sm:text-xs dark:text-slate-400">Kementerian Keuangan</span>
            </span>
        </a>

        {{-- Nav desktop + toggle, dikelompokkan jadi satu klaster di kanan
             agar jaraknya dekat (bukan justify-between antar 3 elemen). --}}
        <div class="flex min-w-0 items-center gap-2">
            <nav class="hidden items-center gap-1 lg:flex">
                <div class="group relative">
                    <button type="button" class="flex items-center gap-1 rounded-full px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-brand-50 hover:text-brand-700 dark:text-slate-300 dark:hover:bg-brand-500/10 dark:hover:text-brand-400">
                        Profil
                        <i class="fa-solid fa-chevron-down text-[10px] opacity-60 transition group-hover:rotate-180"></i>
                    </button>
                    <div class="invisible absolute left-0 top-full z-10 w-64 rounded-2xl border border-slate-100 bg-white p-2 opacity-0 shadow-[var(--shadow-lift)] transition duration-200 group-hover:visible group-hover:opacity-100 dark:border-white/10 dark:bg-navy-900"
                         style="transition-property: opacity, visibility;">
                        @foreach ($feNavProfile as $item)
                            <a href="{{ route($item['route']) }}"
                               class="block rounded-xl px-4 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-brand-50 hover:text-brand-700 dark:text-slate-300 dark:hover:bg-brand-500/10 dark:hover:text-brand-400 {{ request()->routeIs($item['route']) ? 'bg-brand-50 text-brand-700 dark:bg-brand-500/10 dark:text-brand-400' : '' }}">
                                {!! $item['label'] !!}
                            </a>
                        @endforeach
                    </div>
                </div>

                @foreach ($feNavMain as $item)
                    <a href="{{ route($item['route']) }}"
                       class="rounded-full px-4 py-2.5 text-sm font-semibold transition {{ request()->routeIs($item['route'].'*') ? 'bg-brand-50 text-brand-700 dark:bg-brand-500/10 dark:text-brand-400' : 'text-slate-700 hover:bg-brand-50 hover:text-brand-700 dark:text-slate-300 dark:hover:bg-brand-500/10 dark:hover:text-brand-400' }}">
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="flex flex-none items-center gap-1.5">
                {{-- Pencarian global --}}
                <a href="{{ route('search-fe') }}" aria-label="Cari"
                    class="flex h-11 w-11 flex-none items-center justify-center rounded-full text-navy-800 transition hover:bg-brand-50 dark:text-slate-300 dark:hover:bg-white/10">
                    <i class="fa-solid fa-magnifying-glass text-base"></i>
                </a>

                {{-- Toggle mode gelap/terang --}}
                <button type="button" x-data="themeToggle" @click="toggle()" :aria-pressed="dark"
                    aria-label="Ganti tampilan gelap/terang"
                    class="flex h-11 w-11 flex-none items-center justify-center rounded-full text-navy-800 transition hover:bg-brand-50 dark:text-slate-300 dark:hover:bg-white/10">
                    <i class="fa-solid fa-moon text-base" x-show="!dark"></i>
                    <i class="fa-solid fa-sun text-base" x-show="dark" x-cloak></i>
                </button>

                {{-- Tombol hamburger mobile --}}
                <button type="button" @click="mobileOpen = true" aria-label="Buka menu"
                    class="flex h-11 w-11 flex-none items-center justify-center rounded-full text-navy-800 transition hover:bg-brand-50 lg:hidden dark:text-slate-300 dark:hover:bg-white/10">
                    <i class="fa-solid fa-bars-staggered text-lg"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- Panel menu mobile --}}
    <template x-teleport="body">
        <div x-show="mobileOpen" x-cloak @keydown.escape.window="mobileOpen = false" class="fixed inset-0 z-100 lg:hidden">
            <div x-show="mobileOpen" x-transition.opacity @click="mobileOpen = false"
                class="absolute inset-0 bg-navy-950/60 backdrop-blur-sm"></div>

            <div x-show="mobileOpen" x-trap.inert.noscroll="mobileOpen"
                x-transition:enter="transition duration-300 ease-out" x-transition:enter-start="translate-x-full"
                x-transition:enter-end="translate-x-0" x-transition:leave="transition duration-200 ease-in"
                x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
                class="absolute inset-y-0 right-0 flex w-full max-w-xs flex-col overflow-y-auto bg-white shadow-2xl dark:bg-navy-900">

                <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4 dark:border-white/10">
                    <span class="text-sm font-bold text-navy-800 dark:text-white">Menu</span>
                    <button type="button" @click="mobileOpen = false" aria-label="Tutup menu"
                        class="flex h-9 w-9 items-center justify-center rounded-full text-slate-500 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-white/10">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <nav class="flex-1 space-y-1 px-3 py-4">
                    <a href="{{ route('homefe') }}" class="block rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-brand-50 hover:text-brand-700 dark:text-slate-300 dark:hover:bg-brand-500/10 dark:hover:text-brand-400">Beranda</a>
                    <a href="{{ route('search-fe') }}" class="flex items-center gap-2 rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-brand-50 hover:text-brand-700 dark:text-slate-300 dark:hover:bg-brand-500/10 dark:hover:text-brand-400">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i> Cari
                    </a>

                    <div class="px-4 pt-3 pb-1 text-[11px] font-bold tracking-wide text-slate-400 uppercase dark:text-slate-500">Profil</div>
                    @foreach ($feNavProfile as $item)
                        <a href="{{ route($item['route']) }}" class="block rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-brand-50 hover:text-brand-700 dark:text-slate-300 dark:hover:bg-brand-500/10 dark:hover:text-brand-400">
                            {!! $item['label'] !!}
                        </a>
                    @endforeach

                    <div class="mt-2 border-t border-slate-100 pt-2 dark:border-white/10">
                        @foreach ($feNavMain as $item)
                            <a href="{{ route($item['route']) }}" class="block rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-brand-50 hover:text-brand-700 dark:text-slate-300 dark:hover:bg-brand-500/10 dark:hover:text-brand-400">
                                {{ $item['label'] }}
                            </a>
                        @endforeach
                    </div>
                </nav>

                <div class="border-t border-slate-100 px-5 py-4 text-xs text-slate-500 dark:border-white/10 dark:text-slate-400">
                    <a href="mailto:kemenkeu.prime@kemenkeu.go.id" class="flex items-center gap-2 font-medium text-brand-700 dark:text-brand-400">
                        <i class="fa-regular fa-envelope"></i> kemenkeu.prime@kemenkeu.go.id
                    </a>
                </div>
            </div>
        </div>
    </template>
</header>
