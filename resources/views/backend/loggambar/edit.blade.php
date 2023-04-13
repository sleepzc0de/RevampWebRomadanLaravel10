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
							<h5 class="mb-0">Edit Gambar</h5>
						</div>

						<form class="form-validate-jquery" action="{{route('loggambar.update', encrypt($loggambars->id))}}" method="post" enctype="multipart/form-data" autocomplete="off">
							@csrf
                            @method('PUT')
							<div class="card-body">
							
								<div class="mb-4">

									<!-- Judul loggambars input -->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">Nama Gambar <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											<input value="{{ old('nama_gambar') ?? $loggambars->nama_gambar }}" type="text" name="nama_gambar" class="form-control @error('nama_gambar') is-invalid @enderror" required placeholder="Masukkan nama_gambar loggambars">
											<!-- error message untuk nama_gambar -->
											@error('nama_gambar')
											<div class="alert alert-danger mt-2">
												{{ $message }}
											</div>
											@enderror
										</div>
									</div>
									<!-- /Judul loggambars input -->

                                    <!-- Image file uploader -->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">Gambar <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											<input type="file" class="form-control @error('image') is-invalid @enderror" id="customFile" name="image">
											<div class="mt-3">
												<img src="{{asset('storage/romadan_gambar_web/'.$loggambars->image)}}" alt="" width="300px">
											</div>
											
                                            
											@error('image')
											<div class="alert alert-danger mt-2">
												{{ $message }}
											</div>
											@enderror
										</div>
									</div>
									<!-- /image file uploader -->
                                    
								

								</div>

						</div>

						<div class="card-footer d-flex justify-content-end">
							<a href="{{ URL::previous() }}" class="btn btn-warning"><i class="ph-caret-double-left"></i>Kembali</a>
							<a href="{{route('loggambar.index')}}"><button class="btn btn-light ms-3">Batal</button></a>
							<button type="submit" class="btn btn-primary ms-3">Update <i class="ph-paper-plane-tilt ms-2"></i></button>
						</div>
							</form>
					</div>
<!-- /form validation -->
@endsection




