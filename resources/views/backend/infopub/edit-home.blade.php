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
							<h5 class="mb-0">Edit Home Informasi Publik</h5>
						</div>

						<form class="form-validate-jquery" action="{{route('informasi-publik.update-home', encrypt($data->id))}}" method="post" enctype="multipart/form-data" autocomplete="off">
							@csrf
                            @method('PUT')
							<div class="card-body">
							
								<div class="mb-4">

                                <!-- Judul Artikel input -->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">Judul Informasi Publik <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											<input value="{{ old('judul') ?? $data->judul }}" type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" required placeholder="Masukkan Judul kegiatan">
											<!-- error message untuk judul -->
											@error('judul')
											<div class="alert alert-danger mt-2">
												{{ $message }}
											</div>
											@enderror
										</div>
									</div>
									<!-- /Judul Artikel input -->

									<!-- kegiatan -->
									<div class="row mb-3">
										<label class="col-form-label col-lg-2">Isi Informasi Publik <span class="text-danger">*</span></label>
										<div class="col-lg-10">
											<textarea name="isi" class="form-control @error('isi') is-invalid @enderror" required placeholder="Isikan List Informasi" id="ckeditor_classic_empty">{{ old('isi') ?? $data->isi }}</textarea>
										</div>
									</div>
									<!-- /kegiatan -->

								</div>

						</div>

						<div class="card-footer d-flex justify-content-end">
							<a href="{{ URL::previous() }}" class="btn btn-warning"><i class="ph-caret-double-left"></i>Kembali</a>
							<a href="{{route('informasi-publik.index')}}"><button class="btn btn-light ms-3">Batal</button></a>
							<button type="submit" class="btn btn-primary ms-3">Update <i class="ph-paper-plane-tilt ms-2"></i></button>
						</div>
							</form>
					</div>
<!-- /form validation -->
@endsection




