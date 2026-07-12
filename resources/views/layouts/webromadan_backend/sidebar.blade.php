<!-- Main sidebar -->
<div class="sidebar sidebar-dark sidebar-main sidebar-expand-lg">
    <!-- Sidebar content -->
    <div class="sidebar-content">
        <!-- Sidebar header -->
        <div class="sidebar-section">
            <div class="sidebar-section-body d-flex justify-content-center">
                <h5 class="sidebar-resize-hide flex-grow-1 my-auto"><i class="ph-windows-logo"></i> CMS ROMADAN V.1</h5>
                <div>
                    <button type="button" class="btn btn-flat-white btn-icon btn-sm rounded-pill border-transparent sidebar-control sidebar-main-resize d-none d-lg-inline-flex">
                        <i class="ph-arrows-left-right"></i>
                    </button>
                    <button type="button" class="btn btn-flat-white btn-icon btn-sm rounded-pill border-transparent sidebar-mobile-main-toggle d-lg-none">
                        <i class="ph-x"></i>
                    </button>
                </div>
            </div>
        </div>
        <!-- /sidebar header -->

        <!-- Main navigation -->
        <div class="sidebar-section">
            <ul class="nav nav-sidebar" data-nav-type="accordion">
                <!-- Main - Accessible by all roles -->
                <li class="nav-item-header">
                    <div class="text-uppercase fs-sm lh-sm opacity-50 sidebar-resize-hide">Backend</div>
                    <i class="ph-dots-three sidebar-resize-show"></i>
                </li>
                <li class="nav-item">
                    <a href="{{route('home')}}" class="nav-link {{ Request::routeIs('home') ? 'active' : '' }}">
                        <i class="ph-house"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <!-- Profile - For ADMINISTRATOR, REDAKTUR, EDITOR -->
                @if(auth()->user()->hasRole(['ADMINISTRATOR', 'REDAKTUR', 'EDITOR']))
                <li class="nav-item nav-item-submenu {{ Request::is('backend/romadan-interface/profile/*') ? 'nav-item-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="ph-github-logo"></i>
                        <span>Profile</span>
                    </a>
                    <ul class="nav-group-sub collapse {{ Request::is('backend/romadan-interface/profile/*') ? 'show' : '' }}">
                        <li class="nav-item">
                            <a href="{{route('tentang.index')}}" class="nav-link {{ Request::routeIs('tentang.*') ? 'active' : '' }}">Tentang Biro</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('visi-misi.index')}}" class="nav-link {{ Request::routeIs('visi-misi.*') ? 'active' : '' }}">Visi & Misi</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('sejarah.index')}}" class="nav-link {{ Request::routeIs('sejarah.*') ? 'active' : '' }}">Sejarah</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('struktur-organisasi.index')}}" class="nav-link {{ Request::routeIs('struktur-organisasi.*') ? 'active' : '' }}">Struktur Organisasi</a>
                        </li>
                        @role('ADMINISTRATOR')
                        <li class="nav-item">
                            <a href="{{route('layanan.index')}}" class="nav-link {{ Request::routeIs('layanan.*') ? 'active' : '' }}">Layanan</a>
                        </li>
                        @endrole
                    </ul>
                </li>
                @endif

                <!-- Informasi Publik - For ADMINISTRATOR only -->
                @role('ADMINISTRATOR')
                <li class="nav-item nav-item-submenu {{ Request::is('backend/romadan-interface/informasi-publik/*') ? 'nav-item-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="ph-squares-four"></i>
                        <span>Informasi Publik</span>
                    </a>
                    <ul class="nav-group-sub collapse {{ Request::is('backend/romadan-interface/informasi-publik/*') ? 'show' : '' }}">
                        <li class="nav-item">
                            <a href="{{route('informasi-publik.index')}}" class="nav-link {{ Request::routeIs('informasi-publik.*') ? 'active' : '' }}">Home Infopublik</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('peraturan.index')}}" class="nav-link {{ Request::routeIs('peraturan.*') ? 'active' : '' }}">Peraturan</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('pedoman.index')}}" class="nav-link {{ Request::routeIs('pedoman.*') ? 'active' : '' }}">Pedoman</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('aplikasi.index')}}" class="nav-link {{ Request::routeIs('aplikasi.*') ? 'active' : '' }}">Link Aplikasi</a>
                        </li>
                    </ul>
                </li>
                @endrole

                <!-- Publikasi - For ADMINISTRATOR, REDAKTUR, EDITOR, HUMAS_* -->
                @if(auth()->user()->hasRole(['ADMINISTRATOR', 'REDAKTUR', 'EDITOR','HUMAS']) || str_starts_with(auth()->user()->roles->first()->name, 'HUMAS'))
                <li class="nav-item nav-item-submenu {{ Request::is('backend/romadan-interface/publikasi*') ? 'nav-item-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="ph-mask-happy"></i>
                        <span>Publikasi</span>
                    </a>
                    <ul class="nav-group-sub collapse {{ Request::is('backend/romadan-interface/publikasi*') ? 'show' : '' }}">
                        <li class="nav-item">
                            <a href="{{route('publikasi.index')}}" class="nav-link {{ Request::routeIs('publikasi.*') ? 'active' : '' }}">Berita</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('publikasi.index')}}" class="nav-link {{ Request::routeIs('publikasi.*') ? 'active' : '' }}">Warta</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('publikasi.index')}}" class="nav-link {{ Request::routeIs('publikasi.*') ? 'active' : '' }}">Artikel</a>
                        </li>
                    </ul>
                </li>
                @endif

                <!-- FAQ - For ADMINISTRATOR, REDAKTUR, EDITOR -->
                @if(auth()->user()->hasRole(['ADMINISTRATOR', 'REDAKTUR', 'EDITOR']))
                <li class="nav-item">
                    <a href="{{route('faq.index')}}" class="nav-link {{ Request::routeIs('faq.*') ? 'active' : '' }}">
                        <i class="ph-balloon"></i>
                        <span>FAQ</span>
                    </a>
                </li>
                @endif

                <!-- Referensi - For ADMINISTRATOR, REDAKTUR -->
                @if(auth()->user()->hasRole(['ADMINISTRATOR', 'REDAKTUR']))
                <li class="nav-item nav-item-submenu {{ Request::is('backend/romadan-interface/referensi/*') ? 'nav-item-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="ph-slack-logo"></i>
                        <span>Referensi</span>
                    </a>
                    <ul class="nav-group-sub collapse {{ Request::is('backend/romadan-interface/referensi/*') ? 'show' : '' }}">
                        <li class="nav-item">
                            <a href="{{route('status-peraturan.index')}}" class="nav-link {{ Request::routeIs('status-peraturan.*') ? 'active' : '' }}">Status Peraturan</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('jenis-peraturan.index')}}" class="nav-link {{ Request::routeIs('jenis-peraturan.*') ? 'active' : '' }}">Jenis Peraturan</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('kategori.index')}}" class="nav-link {{ Request::routeIs('kategori.*') ? 'active' : '' }}">Kategori Publikasi</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('status.index')}}" class="nav-link {{ Request::routeIs('status.*') ? 'active' : '' }}">Status Publikasi</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('tipe.index')}}" class="nav-link {{ Request::routeIs('tipe.*') ? 'active' : '' }}">Tipe Publikasi</a>
                        </li>
                    </ul>
                </li>
                @endif

                <!-- Interface section - For ADMINISTRATOR only -->
                @role('ADMINISTRATOR')
                <li class="nav-item-header">
                    <div class="text-uppercase fs-sm lh-sm opacity-50 sidebar-resize-hide">Frontend</div>
                    <i class="ph-dots-three sidebar-resize-show"></i>
                </li>
                <li class="nav-item nav-item-submenu {{ Request::is('backend/romadan-interface/interface/*') ? 'nav-item-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="ph-puzzle-piece"></i>
                        <span>Interface</span>
                    </a>
                    <ul class="nav-group-sub collapse {{ Request::is('backend/romadan-interface/interface/*') ? 'show' : '' }}">
                        <li class="nav-item">
                            <a href="{{route('medsos.index')}}" class="nav-link {{ Request::routeIs('medsos.*') ? 'active' : '' }}">Media Sosial</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('loggambar.index')}}" class="nav-link {{ Request::routeIs('loggambar.*') ? 'active' : '' }}">Gambar Login</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('contact-info.index')}}" class="nav-link {{ Request::routeIs('contact-info.*') ? 'active' : '' }}">Informasi Kontak</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('footer-link.index')}}" class="nav-link {{ Request::routeIs('footer-link.*') ? 'active' : '' }}">Tautan Footer</a>
                        </li>
                    </ul>
                </li>

                <!-- User Management section -->
                <li class="nav-item-header">
                    <div class="text-uppercase fs-sm lh-sm opacity-50 sidebar-resize-hide">User Management</div>
                    <i class="ph-dots-three sidebar-resize-show"></i>
                </li>
                <li class="nav-item nav-item-submenu {{ Request::is('backend/romadan-interface/users*') ? 'nav-item-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="ph-layout"></i>
                        <span>User Manajemen</span>
                    </a>
                    <ul class="nav-group-sub collapse {{ Request::is('backend/romadan-interface/users*') ? 'show' : '' }}">
                        <li class="nav-item">
                            <a href="{{route('users.index')}}" class="nav-link {{ Request::routeIs('users.*') ? 'active' : '' }}">List User</a>
                        </li>
                    </ul>
                </li>

                <!-- Tim Pengembang section -->
                <li class="nav-item-header">
                    <div class="text-uppercase fs-sm lh-sm opacity-50 sidebar-resize-hide">EXTRAS</div>
                    <i class="ph-dots-three sidebar-resize-show"></i>
                </li>
                <li class="nav-item">
                    <a href="{{route('pengembang.index')}}" class="nav-link {{ Request::routeIs('pengembang.*') ? 'active' : '' }}">
                        <i class="ph-terminal-window"></i>
                        <span>Tim Pengembang</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('activity-log.index')}}" class="nav-link {{ Request::routeIs('activity-log.*') ? 'active' : '' }}">
                        <i class="ph-clock-counter-clockwise"></i>
                        <span>Log Aktivitas</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('backups.index')}}" class="nav-link {{ Request::routeIs('backups.*') ? 'active' : '' }}">
                        <i class="ph-database"></i>
                        <span>Backup</span>
                    </a>
                </li>
                @endrole
            </ul>
        </div>
        <!-- /main navigation -->
    </div>
    <!-- /sidebar content -->
</div>
<!-- /main sidebar -->
