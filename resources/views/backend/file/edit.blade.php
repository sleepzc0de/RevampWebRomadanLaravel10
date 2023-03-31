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
							<h5 class="mb-0">Edit File</h5>
						</div>

						<form class="form-validate-jquery" action="{{route('file.update', encrypt($file->id))}}" method="post" enctype="multipart/form-data" autocomplete="off">
							@csrf
                            @method('PUT')
							<div class="card-body">
							
								<div class="mb-4">

									<!-- Nama File Inpu -->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">Nama File <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											<input value="{{ old('nama_file') ?? $file->nama_file }}" type="text" name="nama_file" class="form-control @error('nama_file') is-invalid @enderror" required placeholder="Masukkan Nama File">
											<!-- error message untuk nama_file -->
											@error('nama_file')
											<div class="alert alert-danger mt-2">
												{{ $message }}
											</div>
											@enderror
										</div>
									</div>
									<!-- /Nama File Inpu -->

                                    <!-- Image file uploader -->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">File <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											<input type="file" class="form-control @error('image') is-invalid @enderror" id="customFile" name="image_file">
											<div class="mt-3">
												{{$file->image_file}}
											</div>
											
                                            
											@error('image')
											<div class="alert alert-danger mt-2">
												{{ $message }}
											</div>
											@enderror
										</div>
									</div>
									<!-- /image file uploader -->

                                  
									<!-- Isi Berita Input -->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">Deskripsi Isi File <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											<textarea name="isi_file" class="form-control @error('isi_file') is-invalid @enderror" placeholder="Deskripsi Isi File" id="ckeditor_classic_empty">{{ old('isi_file') ?? $file->isi_file }}</textarea>
										</div>
									</div>
									<!-- /Isi Berita Input -->

								</div>

						</div>

						<div class="card-footer d-flex justify-content-end">
							<a href="{{ URL::previous() }}" class="btn btn-warning"><i class="ph-caret-double-left"></i>Kembali</a>
							<a href="{{route('file.index')}}"><button class="btn btn-light ms-3">Batal</button></a>
							<button type="submit" class="btn btn-primary ms-3">Update <i class="ph-paper-plane-tilt ms-2"></i></button>
						</div>
							</form>
					</div>
<!-- /form validation -->
@endsection




