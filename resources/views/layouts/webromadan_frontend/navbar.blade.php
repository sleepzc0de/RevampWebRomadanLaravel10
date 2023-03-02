<!-- Main navbar -->
	<div class="navbar navbar-light navbar-expand-lg navbar-static border-bottom border-bottom-white border-opacity-10">
		<div class="container-fluid">
			<div class="d-flex d-lg-none me-2">
				<button type="button" class="navbar-toggler sidebar-mobile-main-toggle rounded-pill">
					<i class="ph-list"></i>
				</button>
			</div>

			<div class="navbar-brand flex-1 flex-lg-0">
				<a href="index.html" class="d-inline-flex align-items-center">
					{{-- <img src="webromadan/be/images/logo_icon.svg" alt=""> --}}
					 <img class="w-100 h-50" src="{{asset('webromadan/fe/images/romadan/logo_3.png')}}" alt="">
				</a>
			</div>



			<div class="navbar-collapse justify-content-center flex-fill order-2 order-lg-1 collapse" >
                
                {{-- PROFILE --}}
                <div class="nav-item">
                 <div class="btn-group">
                                        <button type="button" class="btn btn-link" data-bs-toggle="dropdown" style="">Profil</button>
                                        <div class="dropdown-menu">
                                            <a href="#" class="dropdown-item">Sejarah</a>
                                            <a href="#" class="dropdown-item">Organisasi</a>
                                            <a href="#" class="dropdown-item">Visi dan Misi</a>
                                            <a href="#" class="dropdown-item">Kode Etik</a>
                                        </div>
                                    </div>
                </div>
                {{-- END PROFILE --}}

                {{-- LAYANAN --}}
                <div class="nav-item">
                 <div class="btn-group">
                                        <button type="button" class="btn btn-link" data-bs-toggle="dropdown">Layanan</button>
                                        <div class="dropdown-menu">
                                            <a href="#" class="dropdown-item">Helpdesk LPSE</a>
                                            <a href="#" class="dropdown-item">Helpdesk BMN (Apa gak disatuin aja?)</a>
                                        </div>
                                    </div>
                </div>
                {{-- END LAYANAN --}}

                {{-- KEGIATAN --}}
                <div class="nav-item">
                  <div class="btn-group">
                                        <button type="button" class="btn btn-link" data-bs-toggle="dropdown">Kegiatan</button>
                                        <div class="dropdown-menu">
                                            <a href="#" class="dropdown-item">Pengelolaan BMN</a>
                                            <a href="#" class="dropdown-item">Pengelolaan Pengadaan Barang/Jasa</a>
                                            <a href="#" class="dropdown-item">Pengelolaan Sistem Informasi</a>
                                        </div>
                                    </div>
                </div>
                {{-- END KEGIATAN --}}

                {{-- INFORMASI PUBLIK --}}
                <div class="nav-item">
                 <div class="btn-group">
                                        <button type="button" class="btn btn-link d" data-bs-toggle="dropdown">Informasi Publik</button>
                                        <div class="dropdown-menu">
                                            <a href="#" class="dropdown-item">Peraturan</a>
                                             <a href="#" class="dropdown-item">Majalah Info Pasti</a>
                                        </div>
                                    </div>
                </div>
                {{-- END INFORMASI PUBLIK --}}

                {{-- PUBLIKASI --}}
                <div class="nav-item">
                 <div class="btn-group">
                                        <button type="button" class="btn btn-link" data-bs-toggle="dropdown">Publikasi</button>
                                        <div class="dropdown-menu">
                                            <a href="#" class="dropdown-item">Artikel Berita</a>
                     <a href="#" class="dropdown-item">Penghargaan</a>
                     <div class="dropdown-submenu">
                         <a href="#" class="dropdown-item dropdown-toggle">Galeri</a>
                         <div class="dropdown-menu">
                             <a href="#" class="dropdown-item">Dokumentasi Kegiatan</a>
                             <a href="#" class="dropdown-item">Webinar</a>
                              <a href="#" class="dropdown-item">Podcast</a>
                         </div>
                     </div>
                                        </div>
                                    </div>
                </div>
                {{-- END PUBLIKASI --}}

                {{-- FAQ --}}
                <div class="nav-item">
                 <div class="btn-group">
                                        <button type="button" class="btn btn-link" data-bs-toggle="dropdown">FAQ</button>
                                        <div class="dropdown-menu">
                                            <div class="dropdown-submenu dropdown-submenu">
												<a href="#" class="dropdown-item">BMN</a>
												<div class="dropdown-menu">
													<a href="#" class="dropdown-item">Perencanaan BMN</a>
													<a href="#" class="dropdown-item">Pengelolaan BMN</a>
                                                    <a href="#" class="dropdown-item">Penatausahaan BMN</a>
												</div>
											</div>
                                            <a href="#" class="dropdown-item">Pengadaan</a>
                                        </div>
                                    </div>
                </div>
                {{-- END FAQ --}}

                

                
			
			</div>

			<div class="navbar-collapse justify-content-end flex-lg-4 order-2 order-lg-1 collapse" id="navbar_search">
				<div class="navbar-search  flex-fill position-relative mt-2 mt-lg-0 mx-lg-3">
                    <form action="{{route('search.berita')}}" method="GET" autocomplete="off">
	                    <div class="form-control-feedback form-control-feedback-start flex-grow-1" data-color-theme="light">
						<input name="cari_berita" value="{{Request::get('cari_berita')}}" type="search" class="form-control bg-transparent rounded-pill" placeholder="Search" data-bs-toggle="dropdown">
						<div class="form-control-feedback-icon">
							<i class="ph-magnifying-glass"></i>
						</div>
					</div>
                    </form>
				
                </div>
                	
				
			</div>
		</div>
	</div>
	<!-- /main navbar -->