@extends('layouts.webromadan_backend.master_layout')

@section('css')
@endsection


@section('script_atas')
<script src="{{asset('webromadan/be/js/jquery/jquery.min.js')}}"></script>
<script src="{{asset('webromadan/be/js/vendor/tables/datatables/datatables.min.js')}}"></script>
@endsection

@section('script_bawah')
<script src="{{asset('webromadan/dist/datatables_warta_sampah.js')}}"></script>
@endsection

@section('content')

<!-- Basic datatable -->
					<div class="card">
						<div class="card-header text-center">
							<h2 class="mb-0">Data Warta Terhapus Romadan</h2>
                             @include('layouts.webromadan_backend.session_notif')
						</div>
						<div class="card-header d-flex justify-content-start">
                            <a href="{{route('warta.index')}}">
								 <button type="button" class="btn btn-flat-success btn-labeled btn-labeled-start rounded-pill">
                                        <span class="btn-labeled-icon bg-success text-white rounded-pill">
                                            <i class="ph-check-square-offset"></i>
                                        </span>
                                        Daftar Warta Aktif
                                    </button>
							</a>
							<form action="{{route('warta.restore-all')}}" method="POST">
                            @csrf
							 <button type="submit" class="btn btn-flat-warning btn-labeled btn-labeled-start rounded-pill ms-2">
                                        <span class="btn-labeled-icon bg-warning text-white rounded-pill">
                                            <i class="ph-check-square-offset"></i>
                                        </span>
                                        Restore Semua Warta
                                    </button>
							</form>
							
						</div>

						<table class="table datatable-basic">
							
							<thead>
								<tr>
									<th>No</th>
									<th>Judul</th>
									<th>Sub Judul</th>
									<th>Gambar Artikel</th>
									<th>Kategori</th>
									<th>Status</th>
									<th class="text-center">Actions</th>
								</tr>
							</thead>
                            <tbody>
                            </tbody>
						</table>
					</div>
					<!-- /basic datatable -->
@endsection
