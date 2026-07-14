{{-- Topbar CMS Romadan V.2 --}}
@php
    $__routeName = Illuminate\Support\Facades\Route::currentRouteName();
    $__crumbMap = [
        'home' => ['label' => 'Dashboard', 'icon' => 'ph-house'],
        'two-factor.*' => ['label' => 'Keamanan Akun', 'icon' => 'ph-shield-check'],
        'tentang.*' => ['group' => 'Profil Biro', 'label' => 'Tentang Biro', 'icon' => 'ph-identification-badge'],
        'visi-misi.*' => ['group' => 'Profil Biro', 'label' => 'Visi & Misi', 'icon' => 'ph-identification-badge'],
        'sejarah.*' => ['group' => 'Profil Biro', 'label' => 'Sejarah', 'icon' => 'ph-identification-badge'],
        'struktur-jabatan.*' => ['group' => 'Profil Biro', 'label' => 'Struktur Organisasi', 'icon' => 'ph-identification-badge'],
        'layanan.*' => ['group' => 'Profil Biro', 'label' => 'Layanan', 'icon' => 'ph-identification-badge'],
        'informasi-publik.*' => ['group' => 'Informasi Publik', 'label' => 'Home Infopublik', 'icon' => 'ph-squares-four'],
        'peraturan.*' => ['group' => 'Informasi Publik', 'label' => 'Peraturan', 'icon' => 'ph-squares-four'],
        'pedoman.*' => ['group' => 'Informasi Publik', 'label' => 'Pedoman', 'icon' => 'ph-squares-four'],
        'aplikasi.*' => ['group' => 'Informasi Publik', 'label' => 'Link Aplikasi', 'icon' => 'ph-squares-four'],
        'publikasi.sampah' => ['group' => 'Publikasi', 'label' => 'Sampah', 'icon' => 'ph-newspaper'],
        'publikasi.*' => ['group' => 'Publikasi', 'label' => 'Semua Publikasi', 'icon' => 'ph-newspaper'],
        'kegiatan.*' => ['label' => 'Kegiatan', 'icon' => 'ph-calendar-check'],
        'faq.*' => ['label' => 'FAQ', 'icon' => 'ph-chats-circle'],
        'status-peraturan.*' => ['group' => 'Referensi', 'label' => 'Status Peraturan', 'icon' => 'ph-books'],
        'jenis-peraturan.*' => ['group' => 'Referensi', 'label' => 'Jenis Peraturan', 'icon' => 'ph-books'],
        'kategori.*' => ['group' => 'Referensi', 'label' => 'Kategori Publikasi', 'icon' => 'ph-books'],
        'status.*' => ['group' => 'Referensi', 'label' => 'Status Publikasi', 'icon' => 'ph-books'],
        'tipe.*' => ['group' => 'Referensi', 'label' => 'Tipe Publikasi', 'icon' => 'ph-books'],
        'medsos.*' => ['group' => 'Interface', 'label' => 'Media Sosial', 'icon' => 'ph-puzzle-piece'],
        'loggambar.*' => ['group' => 'Interface', 'label' => 'Gambar Login', 'icon' => 'ph-puzzle-piece'],
        'contact-info.*' => ['group' => 'Interface', 'label' => 'Informasi Kontak', 'icon' => 'ph-puzzle-piece'],
        'footer-link.*' => ['group' => 'Interface', 'label' => 'Tautan Footer', 'icon' => 'ph-puzzle-piece'],
        'users.*' => ['label' => 'User Manajemen', 'icon' => 'ph-users'],
        'pengembang.*' => ['label' => 'Tim Pengembang', 'icon' => 'ph-terminal-window'],
        'media.*' => ['label' => 'Media Library', 'icon' => 'ph-image-square'],
        'visitors.*' => ['label' => 'Pengunjung', 'icon' => 'ph-users-three'],
        'activity-log.*' => ['label' => 'Log Aktivitas', 'icon' => 'ph-clock-counter-clockwise'],
        'backups.*' => ['label' => 'Backup', 'icon' => 'ph-database'],
    ];

    $__crumb = null;
    foreach ($__crumbMap as $__pattern => $__meta) {
        if ($__routeName && Illuminate\Support\Str::is($__pattern, $__routeName)) {
            $__crumb = $__meta;
            break;
        }
    }
    $__crumb ??= ['label' => 'Dashboard', 'icon' => 'ph-house'];
@endphp

<header x-data="{ scrolled: false }" @scroll.window="scrolled = window.scrollY > 8"
    class="sticky top-0 z-30 flex h-16 shrink-0 items-center gap-3 border-b border-slate-200 bg-white px-4 transition-shadow sm:px-6 dark:border-white/10 dark:bg-navy-900"
    :class="scrolled ? 'shadow-sm' : ''">

    {{-- Toggle sidebar — satu tombol, dua arti: buka drawer di mobile,
         ciutkan/lebarkan rail di desktop (ikon menyesuaikan via lg:) --}}
    <button type="button" x-data class="cms-topbar-btn"
        @click="$store.cms.toggleSidebar()"
        :aria-label="$store.cms.isDesktopWidth ? ($store.cms.sidebarCollapsed ? 'Lebarkan sidebar' : 'Ciutkan sidebar') : 'Buka menu'"
        :title="$store.cms.isDesktopWidth ? ($store.cms.sidebarCollapsed ? 'Lebarkan sidebar' : 'Ciutkan sidebar') : 'Buka menu'">
        {{-- Ikon dibungkus <span> polos (bukan elemen ber-kelas ph-*) supaya
             hidden/lg:hidden Tailwind bisa mengatur tampil/sembunyi. Selektor
             dasar font Phosphor ([class^=ph-]) memaksa display:inline-block
             lewat stylesheet vendor yang unlayered — kalau utility Tailwind
             dipasang langsung di elemen ber-kelas ph-*, ia akan SELALU kalah
             terlepas dari breakpoint (unlayered CSS selalu menang atas CSS
             yang di-layer, apa pun urutan sumbernya). --}}
        <span class="lg:hidden"><i class="ph-list"></i></span>
        <span class="hidden lg:inline"><i class="ph-sidebar-simple"></i></span>
    </button>

    {{-- Breadcrumb dinamis berdasar nama route aktif --}}
    <div class="min-w-0 flex-1">
        @if(($__crumb['group'] ?? null) || $__routeName !== 'home')
            <nav class="hidden items-center gap-1.5 text-[11px] text-slate-400 sm:flex dark:text-slate-500" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="transition hover:text-brand-600 dark:hover:text-brand-400">Dashboard</a>
                @if($__crumb['group'] ?? null)
                    <i class="ph-caret-right text-[8px]"></i>
                    <span>{{ $__crumb['group'] }}</span>
                @endif
            </nav>
        @endif
        <p class="m-0 flex items-center gap-1.5 truncate text-sm font-bold text-slate-800 dark:text-white">
            <i class="{{ $__crumb['icon'] }} text-brand-600 dark:text-brand-400"></i>
            {{ $__crumb['label'] }}
        </p>
    </div>

    {{-- Lihat situs publik --}}
    <a href="{{ url('/') }}" target="_blank" class="cms-topbar-btn" title="Buka situs publik">
        <i class="ph-globe"></i>
    </a>

    {{-- Toggle dark mode --}}
    <button type="button" x-data class="cms-topbar-btn" @click="$store.cms.toggleDark()" title="Mode gelap/terang">
        <span class="dark:hidden"><i class="ph-moon"></i></span>
        <span class="hidden dark:inline"><i class="ph-sun"></i></span>
    </button>

    {{-- Menu user --}}
    <div x-data="{ open: false }" class="relative" @click.outside="open = false" @keydown.escape.window="open = false">
        <button type="button" class="flex cursor-pointer items-center gap-2 rounded-full border-0 bg-transparent py-1 pl-1 pr-2 transition hover:bg-slate-100 dark:hover:bg-white/10" @click="open = !open">
            <span class="flex h-9 w-9 items-center justify-center rounded-full bg-brand-700 text-sm font-bold text-white dark:bg-brand-600">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </span>
            <span class="hidden max-w-36 truncate text-sm font-semibold text-slate-700 md:block dark:text-slate-200">{{ Auth::user()->name }}</span>
            <i class="ph-caret-down hidden text-xs text-slate-400 transition-transform md:block" :class="open ? 'rotate-180' : ''"></i>
        </button>

        <div x-cloak x-show="open" x-transition.origin.top.right
             class="absolute right-0 top-full z-40 mt-2 w-56 rounded-lg border border-slate-200 bg-white py-1.5 shadow-lg dark:border-white/10 dark:bg-navy-900">
            <div class="border-b border-slate-100 px-4 py-2.5 dark:border-white/10">
                <p class="m-0 truncate text-sm font-bold text-slate-800 dark:text-white">{{ Auth::user()->name }}</p>
                <p class="m-0 truncate text-xs text-slate-500 dark:text-slate-400">{{ Auth::user()->email }}</p>
            </div>
            <a href="{{ route('two-factor.index') }}" class="dropdown-item">
                <i class="ph-shield-check me-2"></i> Keamanan Akun
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="dropdown-item text-red-600! dark:text-red-400!">
                    <i class="ph-sign-out me-2"></i> Logout
                </button>
            </form>
        </div>
    </div>
</header>
