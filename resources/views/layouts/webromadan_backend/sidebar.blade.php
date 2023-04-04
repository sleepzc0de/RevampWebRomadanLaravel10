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
                <li class="nav-item-header pt-0">
                    <div class="text-uppercase fs-sm lh-sm opacity-50 sidebar-resize-hide">Main</div>
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

                <!-- Berita -->
                <li class="nav-item-header">
                    <div class="text-uppercase fs-sm lh-sm opacity-50 sidebar-resize-hide">Publikasi</div>
                    <i class="ph-dots-three sidebar-resize-show"></i>
                </li>
                <li class="nav-item nav-item-submenu {{Request::path() == 'backend/berita' ? 'nav-item-open': ''}}">
                    <a href="#" class="nav-link">
                        <i class="ph-note-pencil"></i>
                        <span>Berita</span>
                    </a>
                    <ul class="nav-group-sub collapse {{Request::path() == 'backend/berita' ? 'show': ''}}">
                        <li class="nav-item"><a href="{{route('berita.index')}}" class="nav-link {{Request::path() == 'backend/berita' ? 'active': ''}}">Daftar Berita</a></li>
                    </ul>
                    
                </li>
                 <li class="nav-item nav-item-submenu {{Request::path() == 'backend/warta' ? 'nav-item-open': ''}}">
                    <a href="#" class="nav-link">
                        <i class="ph-note-pencil"></i>
                        <span>Warta</span>
                    </a>
                    <ul class="nav-group-sub collapse {{Request::path() == 'backend/warta' ? 'show': ''}}">
                        <li class="nav-item"><a href="{{route('warta.index')}}" class="nav-link {{Request::path() == 'backend/warta' ? 'active': ''}}">Daftar Warta</a></li>
                    </ul>
                    
                </li>
                 <li class="nav-item nav-item-submenu {{Request::path() == 'backend/artikel' ? 'nav-item-open': ''}}">
                    <a href="#" class="nav-link">
                        <i class="ph-note-pencil"></i>
                        <span>Artikel</span>
                    </a>
                    <ul class="nav-group-sub collapse {{Request::path() == 'backend/artikel' ? 'show': ''}}">
                        <li class="nav-item"><a href="{{route('artikel.index')}}" class="nav-link {{Request::path() == 'backend/artikel' ? 'active': ''}}">Daftar Artikel</a></li>
                    </ul>
                    
                </li>
                <!-- /Berita -->

                 <!-- File -->
                <li class="nav-item-header">
                    <div class="text-uppercase fs-sm lh-sm opacity-50 sidebar-resize-hide">File</div>
                    <i class="ph-dots-three sidebar-resize-show"></i>
                </li>
                <li class="nav-item nav-item-submenu {{Request::path() == 'backend/file' ? 'nav-item-open': ''}}">
                    <a href="#" class="nav-link">
                        <i class="ph-note-pencil"></i>
                        <span>File</span>
                    </a>
                    <ul class="nav-group-sub collapse {{Request::path() == 'backend/file' ? 'show': ''}}">
                        <li class="nav-item"><a href="{{route('file.index')}}" class="nav-link {{Request::path() == 'backend/file' ? 'active': ''}}">Daftar File</a></li>
                    </ul>
                    
                </li>
                <!-- /File -->


                {{-- PROFILE --}}
                <li class="nav-item-header">
                    <div class="text-uppercase fs-sm lh-sm opacity-50 sidebar-resize-hide">Profile</div>
                    <i class="ph-dots-three sidebar-resize-show"></i>
                </li>
                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link">
                        <i class="ph-squares-four"></i>
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
                <li class="nav-item-header">
                    <div class="text-uppercase fs-sm lh-sm opacity-50 sidebar-resize-hide">Layanan</div>
                    <i class="ph-dots-three sidebar-resize-show"></i>
                </li>
                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link">
                        <i class="ph-squares-four"></i>
                        <span>Layanan</span>
                    </a>
                    <ul class="nav-group-sub collapse">
                        <li class="nav-item"><a href="{{route('kategori.index')}}" class="nav-link">Helpdesk LPSE</a></li>
                        <li class="nav-item"><a href="{{route('kategori.index')}}" class="nav-link">Helpdesk BMN</a></li>
                    </ul>
                </li>
                {{-- END LAYANAN --}}

                {{-- INFORMASI PUBLIK --}}
                <li class="nav-item-header">
                    <div class="text-uppercase fs-sm lh-sm opacity-50 sidebar-resize-hide">Info Publik</div>
                    <i class="ph-dots-three sidebar-resize-show"></i>
                </li>
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

                <!-- Referensi -->
                <li class="nav-item-header">
                    <div class="text-uppercase fs-sm lh-sm opacity-50 sidebar-resize-hide">Referensi</div>
                    <i class="ph-dots-three sidebar-resize-show"></i>
                </li>
                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link">
                        <i class="ph-squares-four"></i>
                        <span>Referensi</span>
                    </a>
                    <ul class="nav-group-sub collapse">
                        <li class="nav-item"><a href="{{route('kategori.index')}}" class="nav-link">Kategori Berita</a></li>
                         <li class="nav-item"><a href="{{route('status.index')}}" class="nav-link">Status Berita</a></li>
                    </ul>
                </li>
                <!-- /Referensi -->

                <!-- Gambar-->
                <li class="nav-item-header">
                    <div class="text-uppercase fs-sm lh-sm opacity-50 sidebar-resize-hide">Gambar</div>
                    <i class="ph-dots-three sidebar-resize-show"></i>
                </li>
                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link">
                        <i class="ph-squares-four"></i>
                        <span>Gambar</span>
                    </a>
                    <ul class="nav-group-sub collapse">
                        <li class="nav-item"><a href="{{route('kategori.index')}}" class="nav-link">Slider Home</a></li>
                         <li class="nav-item"><a href="{{route('status.index')}}" class="nav-link">Status Berita</a></li>
                    </ul>
                </li>
                <!-- /Gambar-->

                <!-- Menu-->
                <li class="nav-item-header">
                    <div class="text-uppercase fs-sm lh-sm opacity-50 sidebar-resize-hide">Menu</div>
                    <i class="ph-dots-three sidebar-resize-show"></i>
                </li>
                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link">
                        <i class="ph-squares-four"></i>
                        <span>Menu</span>
                    </a>
                    <ul class="nav-group-sub collapse">
                        <li class="nav-item"><a href="{{route('kategori.index')}}" class="nav-link">Sejarah</a></li>
                        <li class="nav-item"><a href="{{route('kategori.index')}}" class="nav-link">Visi dan Misi</a></li>
                        <li class="nav-item"><a href="{{route('kategori.index')}}" class="nav-link">Struktur Organisasi</a></li>
                        <li class="nav-item"><a href="{{route('kategori.index')}}" class="nav-link">Layanan</a></li>
                    </ul>
                </li>
                <!-- /Menu-->

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