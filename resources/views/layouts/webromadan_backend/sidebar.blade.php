{{-- Sidebar CMS Romadan V.2 — Tailwind + Alpine.
     Status aktif memakai Request::routeIs() (nama route), BUKAN path URL,
     karena seluruh path backend memakai token acak dari config/cms_url.php.
     Rail bisa diciutkan di desktop ($store.cms.sidebarCollapsed) — lihat
     $store.cms.isRailCollapsed di resources/js/backend.js. --}}

{{-- Header sidebar --}}
<div class="flex h-16 shrink-0 items-center justify-between border-b border-white/10 px-5">
    <a href="{{ route('home') }}" class="flex items-center gap-2.5 no-underline" title="CMS Romadan V.2">
        <img src="{{ asset('frontend_romadan_web/images/icons/romadanlogo.png') }}" alt="" class="h-8 w-8 shrink-0 object-contain">
        <span class="leading-tight" x-show="!$store.cms.isRailCollapsed" x-cloak>
            <span class="block text-[13px] font-extrabold tracking-wide text-white">CMS Romadan</span>
            <span class="block text-[10px] font-semibold tracking-[0.14em] text-gold-400 uppercase">V.2</span>
        </span>
    </a>
    <button type="button" class="cms-topbar-btn text-slate-400! lg:hidden" @click="$store.cms.sidebarOpen = false" aria-label="Tutup menu">
        <i class="ph-x"></i>
    </button>
</div>

{{-- Navigasi --}}
<div x-data="{ q: '' }" class="flex min-h-0 flex-1 flex-col">
    {{-- Cari menu --}}
    <div class="px-3.5 pt-3.5" x-show="!$store.cms.isRailCollapsed" x-cloak>
        <div class="relative">
            <i class="ph-magnifying-glass pointer-events-none absolute left-2.5 top-1/2 -translate-y-1/2 text-xs text-slate-500"></i>
            <input type="text" x-model="q" placeholder="Cari menu..." class="cms-sidebar-search" aria-label="Cari menu sidebar">
            <button type="button" x-show="q" x-cloak @click="q = ''"
                class="absolute right-2 top-1/2 -translate-y-1/2 text-xs text-slate-500 transition hover:text-white" aria-label="Bersihkan pencarian">
                <i class="ph-x-circle"></i>
            </button>
        </div>
    </div>

    <nav class="cms-sidebar-nav min-h-0 flex-1 overflow-y-auto px-3.5 py-3">
        <div class="cms-section-label mt-0!" x-show="!$store.cms.isRailCollapsed" x-cloak>Backend</div>

        <a href="{{ route('home') }}" class="cms-sidebar-link {{ Request::routeIs('home') ? 'active' : '' }}" :class="{ 'justify-center px-0': $store.cms.isRailCollapsed }" title="Dashboard" x-show="!q || 'dashboard'.includes(q.toLowerCase())">
            <i class="ph-house"></i><span x-show="!$store.cms.isRailCollapsed" x-cloak>Dashboard</span>
        </a>

        <a href="{{ route('two-factor.index') }}" class="cms-sidebar-link {{ Request::routeIs('two-factor.*') ? 'active' : '' }}" :class="{ 'justify-center px-0': $store.cms.isRailCollapsed }" title="Keamanan Akun" x-show="!q || 'keamanan akun'.includes(q.toLowerCase())">
            <i class="ph-shield-check"></i><span x-show="!$store.cms.isRailCollapsed" x-cloak>Keamanan Akun</span>
        </a>

        {{-- Profile — ADMINISTRATOR, REDAKTUR, EDITOR --}}
        @if(auth()->user()->hasRole(['ADMINISTRATOR', 'REDAKTUR', 'EDITOR']))
            @php
                $profileOpen = Request::routeIs('tentang.*', 'visi-misi.*', 'sejarah.*', 'struktur-jabatan.*', 'layanan.*');
                $profileBlob = strtolower('Profil Biro Tentang Biro Visi & Misi Sejarah Struktur Organisasi Layanan');
            @endphp
            <div x-data="{ open: {{ $profileOpen ? 'true' : 'false' }} }"
                 x-show="!q || @js($profileBlob).includes(q.toLowerCase())"
                 x-effect="if (q && @js($profileBlob).includes(q.toLowerCase())) open = true">
                <button type="button" class="cms-sidebar-link w-full border-0 bg-transparent" :class="{ 'justify-center px-0': $store.cms.isRailCollapsed }" title="Profil Biro"
                    @click="if ($store.cms.isRailCollapsed) { $store.cms.expandSidebar(); open = true } else { open = !open }">
                    <i class="ph-identification-badge"></i>
                    <span class="flex-1 text-left" x-show="!$store.cms.isRailCollapsed" x-cloak>Profil Biro</span>
                    <i class="ph-caret-down text-xs transition-transform" x-show="!$store.cms.isRailCollapsed" x-cloak :class="open ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="open && !$store.cms.isRailCollapsed" x-collapse class="mb-1 ml-6 border-l border-white/10 pl-3">
                    <a href="{{ route('tentang.index') }}" class="cms-sidebar-sub {{ Request::routeIs('tentang.*') ? 'active' : '' }}">Tentang Biro</a>
                    <a href="{{ route('visi-misi.index') }}" class="cms-sidebar-sub {{ Request::routeIs('visi-misi.*') ? 'active' : '' }}">Visi & Misi</a>
                    <a href="{{ route('sejarah.index') }}" class="cms-sidebar-sub {{ Request::routeIs('sejarah.*') ? 'active' : '' }}">Sejarah</a>
                    <a href="{{ route('struktur-jabatan.index') }}" class="cms-sidebar-sub {{ Request::routeIs('struktur-jabatan.*') ? 'active' : '' }}">Struktur Organisasi</a>
                    @role('ADMINISTRATOR')
                        <a href="{{ route('layanan.index') }}" class="cms-sidebar-sub {{ Request::routeIs('layanan.*') ? 'active' : '' }}">Layanan</a>
                    @endrole
                </div>
            </div>
        @endif

        {{-- Informasi Publik — ADMINISTRATOR --}}
        @role('ADMINISTRATOR')
            @php
                $infopubOpen = Request::routeIs('informasi-publik.*', 'peraturan.*', 'pedoman.*', 'aplikasi.*');
                $infopubBlob = strtolower('Informasi Publik Home Infopublik Peraturan Pedoman Link Aplikasi');
            @endphp
            <div x-data="{ open: {{ $infopubOpen ? 'true' : 'false' }} }"
                 x-show="!q || @js($infopubBlob).includes(q.toLowerCase())"
                 x-effect="if (q && @js($infopubBlob).includes(q.toLowerCase())) open = true">
                <button type="button" class="cms-sidebar-link w-full border-0 bg-transparent" :class="{ 'justify-center px-0': $store.cms.isRailCollapsed }" title="Informasi Publik"
                    @click="if ($store.cms.isRailCollapsed) { $store.cms.expandSidebar(); open = true } else { open = !open }">
                    <i class="ph-squares-four"></i>
                    <span class="flex-1 text-left" x-show="!$store.cms.isRailCollapsed" x-cloak>Informasi Publik</span>
                    <i class="ph-caret-down text-xs transition-transform" x-show="!$store.cms.isRailCollapsed" x-cloak :class="open ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="open && !$store.cms.isRailCollapsed" x-collapse class="mb-1 ml-6 border-l border-white/10 pl-3">
                    <a href="{{ route('informasi-publik.index') }}" class="cms-sidebar-sub {{ Request::routeIs('informasi-publik.*') ? 'active' : '' }}">Home Infopublik</a>
                    <a href="{{ route('peraturan.index') }}" class="cms-sidebar-sub {{ Request::routeIs('peraturan.*') ? 'active' : '' }}">Peraturan</a>
                    <a href="{{ route('pedoman.index') }}" class="cms-sidebar-sub {{ Request::routeIs('pedoman.*') ? 'active' : '' }}">Pedoman</a>
                    <a href="{{ route('aplikasi.index') }}" class="cms-sidebar-sub {{ Request::routeIs('aplikasi.*') ? 'active' : '' }}">Link Aplikasi</a>
                </div>
            </div>
        @endrole

        {{-- Publikasi & Kegiatan — ADMINISTRATOR, REDAKTUR, EDITOR, HUMAS --}}
        @if(auth()->user()->hasRole(['ADMINISTRATOR', 'REDAKTUR', 'EDITOR', 'HUMAS']) || str_starts_with(auth()->user()->roles->first()->name, 'HUMAS'))
            @php
                $pubOpen = Request::routeIs('publikasi.*');
                $pubBlob = strtolower('Publikasi Semua Publikasi Sampah');
            @endphp
            <div x-data="{ open: {{ $pubOpen ? 'true' : 'false' }} }"
                 x-show="!q || @js($pubBlob).includes(q.toLowerCase())"
                 x-effect="if (q && @js($pubBlob).includes(q.toLowerCase())) open = true">
                <button type="button" class="cms-sidebar-link w-full border-0 bg-transparent" :class="{ 'justify-center px-0': $store.cms.isRailCollapsed }" title="Publikasi"
                    @click="if ($store.cms.isRailCollapsed) { $store.cms.expandSidebar(); open = true } else { open = !open }">
                    <i class="ph-newspaper"></i>
                    <span class="flex-1 text-left" x-show="!$store.cms.isRailCollapsed" x-cloak>Publikasi</span>
                    <i class="ph-caret-down text-xs transition-transform" x-show="!$store.cms.isRailCollapsed" x-cloak :class="open ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="open && !$store.cms.isRailCollapsed" x-collapse class="mb-1 ml-6 border-l border-white/10 pl-3">
                    <a href="{{ route('publikasi.index') }}" class="cms-sidebar-sub {{ Request::routeIs('publikasi.index', 'publikasi.create', 'publikasi.edit', 'publikasi.show', 'publikasi.revisions') ? 'active' : '' }}">Semua Publikasi</a>
                    <a href="{{ route('publikasi.sampah') }}" class="cms-sidebar-sub {{ Request::routeIs('publikasi.sampah') ? 'active' : '' }}">Sampah</a>
                </div>
            </div>

            <a href="{{ route('kegiatan.index') }}" class="cms-sidebar-link {{ Request::routeIs('kegiatan.*') ? 'active' : '' }}" :class="{ 'justify-center px-0': $store.cms.isRailCollapsed }" title="Kegiatan" x-show="!q || 'kegiatan'.includes(q.toLowerCase())">
                <i class="ph-calendar-check"></i><span x-show="!$store.cms.isRailCollapsed" x-cloak>Kegiatan</span>
            </a>
        @endif

        {{-- FAQ — ADMINISTRATOR, REDAKTUR, EDITOR --}}
        @if(auth()->user()->hasRole(['ADMINISTRATOR', 'REDAKTUR', 'EDITOR']))
            <a href="{{ route('faq.index') }}" class="cms-sidebar-link {{ Request::routeIs('faq.*') ? 'active' : '' }}" :class="{ 'justify-center px-0': $store.cms.isRailCollapsed }" title="FAQ" x-show="!q || 'faq'.includes(q.toLowerCase())">
                <i class="ph-chats-circle"></i><span x-show="!$store.cms.isRailCollapsed" x-cloak>FAQ</span>
            </a>
        @endif

        {{-- Referensi — ADMINISTRATOR, REDAKTUR --}}
        @if(auth()->user()->hasRole(['ADMINISTRATOR', 'REDAKTUR']))
            @php
                $refOpen = Request::routeIs('kategori.*', 'status.*', 'tipe.*', 'jenis-peraturan.*', 'status-peraturan.*');
                $refBlob = strtolower('Referensi Status Peraturan Jenis Peraturan Kategori Publikasi Status Publikasi Tipe Publikasi');
            @endphp
            <div x-data="{ open: {{ $refOpen ? 'true' : 'false' }} }"
                 x-show="!q || @js($refBlob).includes(q.toLowerCase())"
                 x-effect="if (q && @js($refBlob).includes(q.toLowerCase())) open = true">
                <button type="button" class="cms-sidebar-link w-full border-0 bg-transparent" :class="{ 'justify-center px-0': $store.cms.isRailCollapsed }" title="Referensi"
                    @click="if ($store.cms.isRailCollapsed) { $store.cms.expandSidebar(); open = true } else { open = !open }">
                    <i class="ph-books"></i>
                    <span class="flex-1 text-left" x-show="!$store.cms.isRailCollapsed" x-cloak>Referensi</span>
                    <i class="ph-caret-down text-xs transition-transform" x-show="!$store.cms.isRailCollapsed" x-cloak :class="open ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="open && !$store.cms.isRailCollapsed" x-collapse class="mb-1 ml-6 border-l border-white/10 pl-3">
                    <a href="{{ route('status-peraturan.index') }}" class="cms-sidebar-sub {{ Request::routeIs('status-peraturan.*') ? 'active' : '' }}">Status Peraturan</a>
                    <a href="{{ route('jenis-peraturan.index') }}" class="cms-sidebar-sub {{ Request::routeIs('jenis-peraturan.*') ? 'active' : '' }}">Jenis Peraturan</a>
                    <a href="{{ route('kategori.index') }}" class="cms-sidebar-sub {{ Request::routeIs('kategori.*') ? 'active' : '' }}">Kategori Publikasi</a>
                    <a href="{{ route('status.index') }}" class="cms-sidebar-sub {{ Request::routeIs('status.*') ? 'active' : '' }}">Status Publikasi</a>
                    <a href="{{ route('tipe.index') }}" class="cms-sidebar-sub {{ Request::routeIs('tipe.*') ? 'active' : '' }}">Tipe Publikasi</a>
                </div>
            </div>
        @endif

        @role('ADMINISTRATOR')
            <div class="cms-section-label" x-show="!$store.cms.isRailCollapsed" x-cloak>Frontend</div>

            @php
                $interfaceOpen = Request::routeIs('medsos.*', 'loggambar.*', 'contact-info.*', 'footer-link.*');
                $interfaceBlob = strtolower('Interface Media Sosial Gambar Login Informasi Kontak Tautan Footer');
            @endphp
            <div x-data="{ open: {{ $interfaceOpen ? 'true' : 'false' }} }"
                 x-show="!q || @js($interfaceBlob).includes(q.toLowerCase())"
                 x-effect="if (q && @js($interfaceBlob).includes(q.toLowerCase())) open = true">
                <button type="button" class="cms-sidebar-link w-full border-0 bg-transparent" :class="{ 'justify-center px-0': $store.cms.isRailCollapsed }" title="Interface"
                    @click="if ($store.cms.isRailCollapsed) { $store.cms.expandSidebar(); open = true } else { open = !open }">
                    <i class="ph-puzzle-piece"></i>
                    <span class="flex-1 text-left" x-show="!$store.cms.isRailCollapsed" x-cloak>Interface</span>
                    <i class="ph-caret-down text-xs transition-transform" x-show="!$store.cms.isRailCollapsed" x-cloak :class="open ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="open && !$store.cms.isRailCollapsed" x-collapse class="mb-1 ml-6 border-l border-white/10 pl-3">
                    <a href="{{ route('medsos.index') }}" class="cms-sidebar-sub {{ Request::routeIs('medsos.*') ? 'active' : '' }}">Media Sosial</a>
                    <a href="{{ route('loggambar.index') }}" class="cms-sidebar-sub {{ Request::routeIs('loggambar.*') ? 'active' : '' }}">Gambar Login</a>
                    <a href="{{ route('contact-info.index') }}" class="cms-sidebar-sub {{ Request::routeIs('contact-info.*') ? 'active' : '' }}">Informasi Kontak</a>
                    <a href="{{ route('footer-link.index') }}" class="cms-sidebar-sub {{ Request::routeIs('footer-link.*') ? 'active' : '' }}">Tautan Footer</a>
                </div>
            </div>

            <div class="cms-section-label" x-show="!$store.cms.isRailCollapsed" x-cloak>User Management</div>
            <a href="{{ route('users.index') }}" class="cms-sidebar-link {{ Request::routeIs('users.*') ? 'active' : '' }}" :class="{ 'justify-center px-0': $store.cms.isRailCollapsed }" title="User Manajemen" x-show="!q || 'user manajemen'.includes(q.toLowerCase())">
                <i class="ph-users"></i><span x-show="!$store.cms.isRailCollapsed" x-cloak>User Manajemen</span>
            </a>

            <div class="cms-section-label" x-show="!$store.cms.isRailCollapsed" x-cloak>Extras</div>
            <a href="{{ route('pengembang.index') }}" class="cms-sidebar-link {{ Request::routeIs('pengembang.*') ? 'active' : '' }}" :class="{ 'justify-center px-0': $store.cms.isRailCollapsed }" title="Tim Pengembang" x-show="!q || 'tim pengembang'.includes(q.toLowerCase())">
                <i class="ph-terminal-window"></i><span x-show="!$store.cms.isRailCollapsed" x-cloak>Tim Pengembang</span>
            </a>
            <a href="{{ route('media.index') }}" class="cms-sidebar-link {{ Request::routeIs('media.*') ? 'active' : '' }}" :class="{ 'justify-center px-0': $store.cms.isRailCollapsed }" title="Media Library" x-show="!q || 'media library'.includes(q.toLowerCase())">
                <i class="ph-image-square"></i><span x-show="!$store.cms.isRailCollapsed" x-cloak>Media Library</span>
            </a>
            <a href="{{ route('visitors.index') }}" class="cms-sidebar-link {{ Request::routeIs('visitors.*') ? 'active' : '' }}" :class="{ 'justify-center px-0': $store.cms.isRailCollapsed }" title="Pengunjung" x-show="!q || 'pengunjung'.includes(q.toLowerCase())">
                <i class="ph-users-three"></i><span x-show="!$store.cms.isRailCollapsed" x-cloak>Pengunjung</span>
            </a>
            <a href="{{ route('activity-log.index') }}" class="cms-sidebar-link {{ Request::routeIs('activity-log.*') ? 'active' : '' }}" :class="{ 'justify-center px-0': $store.cms.isRailCollapsed }" title="Log Aktivitas" x-show="!q || 'log aktivitas'.includes(q.toLowerCase())">
                <i class="ph-clock-counter-clockwise"></i><span x-show="!$store.cms.isRailCollapsed" x-cloak>Log Aktivitas</span>
            </a>
            <a href="{{ route('system-monitor.index') }}" class="cms-sidebar-link {{ Request::routeIs('system-monitor.*') ? 'active' : '' }}" :class="{ 'justify-center px-0': $store.cms.isRailCollapsed }" title="Monitor Sistem" x-show="!q || 'monitor sistem resource server database'.includes(q.toLowerCase())">
                <i class="ph-gauge"></i><span x-show="!$store.cms.isRailCollapsed" x-cloak>Monitor Sistem</span>
            </a>
            <a href="{{ route('backups.index') }}" class="cms-sidebar-link {{ Request::routeIs('backups.*') ? 'active' : '' }}" :class="{ 'justify-center px-0': $store.cms.isRailCollapsed }" title="Backup" x-show="!q || 'backup'.includes(q.toLowerCase())">
                <i class="ph-database"></i><span x-show="!$store.cms.isRailCollapsed" x-cloak>Backup</span>
            </a>
        @endrole
    </nav>
</div>

{{-- Kartu user di bawah sidebar — buka menu cepat (Keamanan Akun / Logout) --}}
<div class="relative shrink-0 border-t border-white/10 p-2" x-data="{ open: false }" @click.outside="open = false" @keydown.escape.window="open = false">
    <button type="button" class="flex w-full items-center gap-3 rounded-md border-0 bg-transparent p-2 text-left transition hover:bg-white/[.06]"
        :class="{ 'justify-center': $store.cms.isRailCollapsed }" title="{{ Auth::user()->name }}" @click="open = !open">
        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-brand-600/30 text-sm font-bold text-brand-200">
            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
        </span>
        <span class="min-w-0 flex-1" x-show="!$store.cms.isRailCollapsed" x-cloak>
            <span class="block truncate text-sm font-semibold text-white">{{ Auth::user()->name }}</span>
            <span class="block truncate text-[11px] text-slate-400">{{ Auth::user()->roles->first()->name ?? '' }}</span>
        </span>
        <i class="ph-caret-up shrink-0 text-xs text-slate-500 transition-transform" x-show="!$store.cms.isRailCollapsed" x-cloak :class="open ? '' : 'rotate-180'"></i>
    </button>

    <div x-cloak x-show="open" x-transition.origin.bottom
         class="absolute bottom-full left-2 z-40 mb-1 w-56 rounded-lg border border-white/10 bg-navy-800 py-1.5 shadow-lg">
        <a href="{{ route('two-factor.index') }}" class="dropdown-item text-slate-200! hover:bg-white/5! hover:text-white!">
            <i class="ph-shield-check me-2"></i> Keamanan Akun
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="dropdown-item text-red-400! hover:bg-white/5!">
                <i class="ph-sign-out me-2"></i> Logout
            </button>
        </form>
    </div>
</div>
