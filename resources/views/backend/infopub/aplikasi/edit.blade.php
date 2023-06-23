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
							<h5 class="mb-0">Edit Aplikasi</h5>
						</div>

						<form class="form-validate-jquery" action="{{route('aplikasi.update', encrypt($data->id))}}" method="post" enctype="multipart/form-data" autocomplete="off">
							@csrf
                            @method('PUT')
							<div class="card-body">
							
								<div class="mb-4">

                                <!-- Judul Aplikasi -->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">Judul Aplikasi <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											<input value="{{ old('judul_aplikasi') ?? $data->judul_aplikasi }}" type="text" name="judul_aplikasi" class="form-control @error('judul_aplikasi') is-invalid @enderror" required placeholder="Masukkan Judul Aplikasi">
											<!-- error message untuk judul_aplikasi -->
											@error('judul_aplikasi')
											<div class="alert alert-danger mt-2">
												{{ $message }}
											</div>
											@enderror
										</div>
									</div>
								<!-- /Judul Aplikasi -->
                                <!-- Sub Judul Aplikasi -->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">Judul Aplikasi <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											<input value="{{ old('sub_judul_aplikasi') ?? $data->sub_judul_aplikasi }}" type="text" name="sub_judul_aplikasi" class="form-control @error('sub_judul_aplikasi') is-invalid @enderror" required placeholder="Masukkan Judul Aplikasi">
											<!-- error message untuk sub_judul_aplikasi -->
											@error('sub_judul_aplikasi')
											<div class="alert alert-danger mt-2">
												{{ $message }}
											</div>
											@enderror
										</div>
									</div>
								<!-- /Sub Judul Aplikasi -->
                                <!-- Link Aplikasi-->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">Link Aplikasi <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											<input value="{{ old('link_aplikasi') ?? $data->link_aplikasi }}" type="text" name="link_aplikasi" class="form-control @error('link_aplikasi') is-invalid @enderror" required placeholder="Masukkan Judul Aplikasi">
											<!-- error message untuk link_aplikasi -->
											@error('link_aplikasi')
											<div class="alert alert-danger mt-2">
												{{ $message }}
											</div>
											@enderror
										</div>
									</div>
								<!-- /Link Aplikasi-->
								<!-- Image file uploader -->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">Gambar Aplikasi <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											<input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image">
											<div class="mt-3">
												<img src="{{asset('storage/romadan_gambar_web/'.$data->image)}}" alt="" width="300px">
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
							<a href="{{route('aplikasi.index')}}" class="btn btn-warning"><i class="ph-caret-double-left"></i>Kembali</a>
							<a href="{{route('publikasi.index')}}"><button class="btn btn-light ms-3">Batal</button></a>
							<button type="submit" class="btn btn-primary ms-3">Update <i class="ph-paper-plane-tilt ms-2"></i></button>
						</div>
							</form>
					</div>
<!-- /form validation -->
@endsection




