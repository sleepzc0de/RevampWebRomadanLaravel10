@extends('layouts.webromadan_backend.master_layout')

@section('css')
@endsection


@section('script_atas')
<script src="{{asset('webromadan/be/js/jquery/jquery.min.js')}}"></script>
<script src="{{asset('webromadan/be/js/vendor/tables/datatables/datatables.min.js')}}"></script>
<script src="{{asset('webromadan/be/js/vendor/tables/datatables/extensions/responsive.min.js')}}"></script>
@endsection

@section('script_bawah')
<script src="{{asset('webromadan/be/demo/pages/datatables_basic.js')}}"></script>

@endsection


@section('content')

<div class="row">
    <div class="col-lg-6">
            <div class="row">
                <div class="col-xl-6 col-sm-6">
                                    <div class="card bg-white text-dark" style="background-image: url({{asset('webromadan/be/images/backgrounds/panel_bg.png')}}); background-size: contain;">
                                            <div class="card-body text-justify">
                                                <div class="card-img-actions d-inline-block mb-3">
                                                    
                                                    <i class="ph-bell-simple-slash ph-2x me-3"></i> 
                                                    
                                                </div>

                                                <h2 class="mb-0">100</h2>
                                                <span class="opacity-75">Jumlah Artikel</span>

                                            </div>
                                    </div>
                </div>
                <div class="col-xl-6 col-sm-6">
                                    <div class="card bg-white text-dark" style="background-image: url({{asset('webromadan/be/images/backgrounds/panel_bg.png')}}); background-size: contain;">
                                            <div class="card-body text-justify">
                                                <div class="card-img-actions d-inline-block mb-3">
                                                    
                                                    <i class="ph-bell-simple-slash ph-2x me-3"></i> 
                                                    
                                                </div>

                                                <h2 class="mb-0">100</h2>
                                                <span class="opacity-75">Jumlah Berita</span>

                                            </div>
                                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-6 col-sm-6">
                                    <div class="card bg-white text-dark" style="background-image: url({{asset('webromadan/be/images/backgrounds/panel_bg.png')}}); background-size: contain;">
                                            <div class="card-body text-justify">
                                                <div class="card-img-actions d-inline-block mb-3">
                                                    
                                                    <i class="ph-bell-simple-slash ph-2x me-3"></i> 
                                                    
                                                </div>

                                                <h2 class="mb-0">100</h2>
                                                <span class="opacity-75">Jumlah Warta</span>

                                            </div>
                                    </div>
                </div>
                <div class="col-xl-6 col-sm-6">
                                    <div class="card bg-white text-dark" style="background-image: url({{asset('webromadan/be/images/backgrounds/panel_bg.png')}}); background-size: contain;">
                                            <div class="card-body text-justify">
                                                <div class="card-img-actions d-inline-block mb-3">
                                                    
                                                    <i class="ph-bell-simple-slash ph-2x me-3"></i> 
                                                    
                                                </div>

                                                <h2 class="mb-0">100</h2>
                                                <span class="opacity-75">Jumlah Kegiatan</span>

                                            </div>
                                    </div>
                </div>
            </div>
    </div>
    <div class="col-lg-6">
        <!-- Basic datatable -->
					<div class="card">
						<div class="card-header">
							<h5 class="mb-0">Content Activity</h5>
						</div>
						<table class="table datatable-basic">
							<thead>
								<tr>
									<th>TANGGAL</th>
									<th>TIPE</th>
									<th>JUDUL</th>
									<th>AKSI</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>Marth</td>
									<td><a href="#">Enright</a></td>
									<td>Traffic Court Referee</td>
									<td>22 Jun 1972</td>
									<td class="text-center">
										<div class="d-inline-flex">
											<div class="dropdown">
												<a href="#" class="text-body" data-bs-toggle="dropdown">
													<i class="ph-list"></i>
												</a>

												<div class="dropdown-menu dropdown-menu-end">
													<a href="#" class="dropdown-item">
														<i class="ph-file-pdf me-2"></i>
														Export to .pdf
													</a>
													<a href="#" class="dropdown-item">
														<i class="ph-file-xls me-2"></i>
														Export to .csv
													</a>
													<a href="#" class="dropdown-item">
														<i class="ph-file-doc me-2"></i>
														Export to .doc
													</a>
												</div>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>Jackelyn</td>
									<td>Weible</td>
									<td><a href="#">Airline Transport Pilot</a></td>
									<td>3 Oct 1981</td>
									<td class="text-center">
										<div class="d-inline-flex">
											<div class="dropdown">
												<a href="#" class="text-body" data-bs-toggle="dropdown">
													<i class="ph-list"></i>
												</a>

												<div class="dropdown-menu dropdown-menu-end">
													<a href="#" class="dropdown-item">
														<i class="ph-file-pdf me-2"></i>
														Export to .pdf
													</a>
													<a href="#" class="dropdown-item">
														<i class="ph-file-xls me-2"></i>
														Export to .csv
													</a>
													<a href="#" class="dropdown-item">
														<i class="ph-file-doc me-2"></i>
														Export to .doc
													</a>
												</div>
											</div>
										</div>
									</td>
								</tr>
								
							</tbody>
						</table>
					</div>
					<!-- /basic datatable -->
    </div>
   
</div>





@endsection
