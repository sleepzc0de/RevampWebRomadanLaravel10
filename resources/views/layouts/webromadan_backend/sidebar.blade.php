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

                <!-- Main -->
                <li class="nav-item-header">
                    <div class="text-uppercase fs-sm lh-sm opacity-50 sidebar-resize-hide">Backend</div>
                    <i class="ph-dots-three sidebar-resize-show"></i>
                </li>
                <li class="nav-item">
                    <a href="{{route('home')}}" class="nav-link">
                        <i class="ph-house"></i>
                        <span>
                            Dashboard
                        </span>
                    </a>
                </li>

                {{-- PUBLIKASI --}}
                {{-- <li class="nav-item-header">
                    <div class="text-uppercase fs-sm lh-sm opacity-50 sidebar-resize-hide">Publikasi</div>
                    <i class="ph-dots-three sidebar-resize-show"></i>
                </li> --}}
                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link">
                        <i class="ph-mask-happy"></i>
                        <span>Publikasi</span>
                    </a>
                    <ul class="nav-group-sub collapse">
                         <li class="nav-item"><a href="{{route('publikasi.index')}}" class="nav-link">Publikasi</a></li>
                         {{-- <li class="nav-item"><a href="{{route('berita.index')}}" class="nav-link">Berita</a></li>
                        <li class="nav-item"><a href="{{route('warta.index')}}" class="nav-link">Warta</a></li>
                        <li class="nav-item"><a href="{{route('artikel.index')}}" class="nav-link">Artikel</a></li> --}}
                    </ul>
                </li>
                {{-- END PUBLIKASI --}}

                {{-- PROFILE --}}
                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link">
                        <i class="ph-github-logo"></i>
                        <span>Profile</span>
                    </a>
                    <ul class="nav-group-sub collapse">
                         <li class="nav-item"><a href="{{route('tentang.index')}}" class="nav-link">Tentang</a></li>
                        <li class="nav-item"><a href="{{route('visi-misi.index')}}" class="nav-link">Visi dan Misi</a></li>
                        <li class="nav-item"><a href="{{route('sejarah.index')}}" class="nav-link">Sejarah</a></li>
                        <li class="nav-item"><a href="{{route('struktur-organisasi.index')}}" class="nav-link">Struktur Organisasi</a></li>
                    </ul>
                </li>
                {{-- END PROFILE --}}

                {{-- LAYANAN --}}
                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link">
                        <i class="ph-butterfly"></i>
                        <span>Layanan</span>
                    </a>
                    <ul class="nav-group-sub collapse">
                        <li class="nav-item"><a href="{{route('layanan.index')}}" class="nav-link">Layanan</a></li>
                    </ul>
                </li>
                {{-- END LAYANAN --}}
                
                {{-- KEGIATAN --}}
                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link">
                        <i class="ph-bicycle"></i>
                        <span>Kegiatan</span>
                    </a>
                    <ul class="nav-group-sub collapse">
                        <li class="nav-item"><a href="{{route('kegiatan.index')}}" class="nav-link">Kegiatan</a></li>
                    </ul>
                </li>
                {{-- END KEGIATAN --}}

                {{-- INFORMASI PUBLIK --}}
                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link">
                        <i class="ph-squares-four"></i>
                        <span>Informasi Publik</span>
                    </a>
                    <ul class="nav-group-sub collapse">
                        <li class="nav-item"><a href="{{route('kategori.index')}}" class="nav-link">Peraturan</a></li>
                        <li class="nav-item"><a href="{{route('kategori.index')}}" class="nav-link">Pedoman</a></li>
                        <li class="nav-item"><a href="{{route('kategori.index')}}" class="nav-link">Link Aplikasi</a></li>
                    </ul>
                </li>
                {{-- END INFORMASI PUBLIK --}}
                

                {{-- REFERENSI --}}
                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link">
                        <i class="ph-slack-logo"></i>
                        <span>Referensi</span>
                    </a>
                    <ul class="nav-group-sub collapse">
                        <li class="nav-item"><a href="{{route('kategori.index')}}" class="nav-link">Kategori</a></li>
                         <li class="nav-item"><a href="{{route('status.index')}}" class="nav-link">Status</a></li>
                          <li class="nav-item"><a href="{{route('tipe.index')}}" class="nav-link">Tipe</a></li>
                    </ul>
                </li>
                {{-- REFERENSI --}}


                {{-- FRONTEND SISTEM --}}
                <li class="nav-item-header">
                    <div class="text-uppercase fs-sm lh-sm opacity-50 sidebar-resize-hide">Frontend</div>
                    <i class="ph-dots-three sidebar-resize-show"></i>
                </li>
                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link">
                        <i class="ph-puzzle-piece"></i>
                        <span>Interface</span>
                    </a>
                    <ul class="nav-group-sub collapse">
                        <li class="nav-item"><a href="{{route('medsos.index')}}" class="nav-link">Media Sosial</a></li>
                        <li class="nav-item"><a href="{{route('loggambar.index')}}" class="nav-link">Gambar Login</a></li>
                    </ul>
                </li>
                {{-- END FRONTEND SISTEM --}}

                <!-- User Management -->
                <li class="nav-item-header">
                    <div class="text-uppercase fs-sm lh-sm opacity-50 sidebar-resize-hide">User Management</div>
                    <i class="ph-dots-three sidebar-resize-show"></i>
                </li>
                <li class="nav-item nav-item-submenu {{Request::path() == 'backend/users' ? 'nav-item-open': ''}}">
                    <a href="#" class="nav-link ">
                        <i class="ph-layout"></i>
                        <span>User Manajemen</span>
                    </a>

                    <ul class="nav-group-sub collapse {{Request::path() == 'backend/users' ? 'show': ''}}">
                        <li class="nav-item"><a href="{{route('users.index')}}" class="nav-link {{Request::path() == 'backend/users' ? 'active': ''}}">List User</a></li>
                    </ul>
                </li>
                <!-- /User Management -->

            </ul>
        </div>
        <!-- /main navigation -->

    </div>
    <!-- /sidebar content -->

</div>
<!-- /main sidebar -->