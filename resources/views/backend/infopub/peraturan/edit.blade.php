@extends('layouts.webromadan_backend.master_layout')

@section('css')
<script src="{{asset('webromadan/be/js/jquery/jquery.min.js')}}"></script>
<script src="{{asset('webromadan/be/js/vendor/tables/datatables/datatables.min.js')}}"></script>
@endsection


@section('script_atas')
<script src="{{asset('webromadan/be/js/jquery/jquery.min.js')}}"></script>
<script src="{{asset('webromadan/be/js/vendor/forms/validation/validate.min.js')}}"></script>
<script src="{{asset('webromadan/be/js/vendor/forms/selects/select2.min.js')}}"></script>
<script src="{{asset('webromadan/be/js/vendor/editors/ckeditor/ckeditor_classic.js')}}"></script>
@endsection

@section('script_bawah')
<script src="{{asset('webromadan/be/demo/pages/form_validation_library.js')}}"></script>
<script src="{{asset('webromadan/be/demo/pages/form_select2.js')}}"></script>
<script src="{{asset('webromadan/be/demo/pages/editor_ckeditor_classic.js')}}"></script>
@endsection

@section('content')

<!-- Form validation -->
					<div class="card">
						<div class="card-header">
							<h5 class="mb-0">Edit Peraturan</h5>
						</div>

						<form class="form-validate-jquery" action="{{route('peraturan.update', encrypt($peraturan->id))}}" method="post" enctype="multipart/form-data" autocomplete="off">
							@csrf
                            @method('PUT')
							<div class="card-body">
							
								<div class="mb-4">

                                <!-- Nomor Peraturan-->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">Nomor Peraturan <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											<input value="{{ old('nomor_peraturan') ?? $peraturan->nomor_peraturan }}" type="text" name="nomor_peraturan" class="form-control @error('nomor_peraturan') is-invalid @enderror" required placeholder="Masukkan Nomor Peraturan">
											<!-- error message untuk nomor_peraturan -->
											@error('nomor_peraturan')
											<div class="alert alert-danger mt-2">
												{{ $message }}
											</div>
											@enderror
										</div>
									</div>
									<!-- /Nomor Peraturan-->

                                     <!-- Judul Peraturan-->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">Nomor Peraturan <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											<input value="{{ old('judul_peraturan') ?? $peraturan->judul_peraturan }}" type="text" name="judul_peraturan" class="form-control @error('judul_peraturan') is-invalid @enderror" required placeholder="Masukkan Judul Peraturan">
											<!-- error message untuk judul_peraturan -->
											@error('judul_peraturan')
											<div class="alert alert-danger mt-2">
												{{ $message }}
											</div>
											@enderror
										</div>
									</div>
									<!-- /Judul Peraturan-->
                                
									 <!-- File Kegiatan -->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">File Peraturan <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											<input type="file" class="form-control @error('file') is-invalid @enderror" id="file" name="file">
											<div class="mt-3">
												{{$peraturan->file}}
											</div>

											@error('file')
											<div class="alert alert-danger mt-2">
												{{ $message }}
											</div>
											@enderror
										</div>
									</div>
									<!-- /File Kegiatan -->

                                    
                                    <!-- /Kategori Peraturan-->
                                    <div class="row mb-3">
										<label class="col-form-label col-lg-2">Kategori Peraturan <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											<select id="kategori" name="kategori" class="form-control form-control-select2 select" @error('kategori') is-invalid @enderror required>
												<option>--PILIH--</option>
												@foreach ($kategori as $item)
												<option value="{{ $item->id_kategori }}" {{ old('kategori',$peraturan->kategori) == $item->id_kategori ? 'selected' : ''}}>{{$loop->iteration." - ".$item->nama_kategori}}</option>
												@endforeach
													
												
											</select>

											<!-- error message untuk judul -->
											@error('kategori')
											<div class="alert alert-danger mt-2">
												{{ $message }}
											</div>
											@enderror
										</div>
									</div>
									<!-- /Kategori Peraturan -->

                                     <!-- /Jenis Peraturan-->
                                    <div class="row mb-3">
										<label class="col-form-label col-lg-2">Jenis Peraturan <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											<select id="jenis_peraturan" name="jenis_peraturan" class="form-control form-control-select2 select" @error('jenis_peraturan') is-invalid @enderror required>
												<option>--PILIH--</option>
												@foreach ($jenis_peraturan as $item)
												<option value="{{ $item->id_jenis_peraturan }}" {{ old('jenis_peraturan',$peraturan->jenis_peraturan) == $item->id_jenis_peraturan ? 'selected' : ''}}>{{$loop->iteration." - ".$item->nama_jenis_peraturan}}</option>
												@endforeach
													
												
											</select>

											<!-- error message untuk judul -->
											@error('jenis_peraturan')
											<div class="alert alert-danger mt-2">
												{{ $message }}
											</div>
											@enderror
										</div>
									</div>
									<!-- /Jenis Peraturan-->

									<!-- Tanggal Penetapan-->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">Tanggal Penetapan <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											<input class="form-control @error('tanggal_penetapan') is-invalid @enderror required" id="tanggal_penetapan" name="tanggal_penetapan" type="date" value="{{ old('tanggal_penetapan') ?? $peraturan->tanggal_penetapan }}">
											@error('tanggal_penetapan')
											<div class="alert alert-danger mt-2">
												{{ $message }}
											</div>
											@enderror
										</div>
									</div>
									<!-- /Tanggal Penetapan-->

									<!-- Tanggal Penetapan-->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">Tanggal Berlaku <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											<input class="form-control @error('tanggal_berlaku') is-invalid @enderror required" id="tanggal_berlaku" name="tanggal_berlaku" type="date" value="{{ old('tanggal_berlaku') ?? $peraturan->tanggal_berlaku }}">
											@error('tanggal_berlaku')
											<div class="alert alert-danger mt-2">
												{{ $message }}
											</div>
											@enderror
										</div>
									</div>
									<!-- /Tanggal Penetapan-->

                                     <!-- /Status Peraturan-->
                                    <div class="row mb-3">
										<label class="col-form-label col-lg-2">Jenis Peraturan <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											<select id="status_peraturan" name="status_peraturan" class="form-control form-control-select2 select" @error('status_peraturan') is-invalid @enderror required>
												<option>--PILIH--</option>
												@foreach ($status_peraturan as $item)
												<option value="{{ $item->id_ref_peraturan_status }}" {{ old('status_peraturan',$peraturan->status_peraturan) == $item->id_ref_peraturan_status ? 'selected' : ''}}>{{$loop->iteration." - ".$item->nama_peraturan_status}}</option>
												@endforeach
													
												
											</select>

											<!-- error message untuk judul -->
											@error('status_peraturan')
											<div class="alert alert-danger mt-2">
												{{ $message }}
											</div>
											@enderror
										</div>
									</div>
									<!-- /Status Peraturan-->

								</div>

						</div>

						<div class="card-footer d-flex justify-content-end">
							<a href="{{route('peraturan.index')}}" class="btn btn-warning"><i class="ph-caret-double-left"></i>Kembali</a>
							<a href="{{route('peraturan.index')}}"><button class="btn btn-light ms-3">Batal</button></a>
							<button type="submit" class="btn btn-primary ms-3">Update <i class="ph-paper-plane-tilt ms-2"></i></button>
						</div>
							</form>
					</div>
<!-- /form validation -->
@endsection




